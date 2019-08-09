<?php

namespace App\Http\Controllers\Api\Forward;


use App\Http\Controllers\Api\ApiController;


use App\Models\BindSecret;
use App\Proto\C2S_AGLogin;
use App\Proto\S2C_AGLoginResult;
use App\Support\PHPGangsta_GoogleAuthenticator;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class LoginController extends ApiController
{
	use  Common;
    private  $host;
	public function __construct()
	{
		parent::__construct();
		$this->host = request()->getHost();
	}

	/**
	 * admin 后台用户登录接口
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	// public function gmLogin1(Request $request)
	// {
	// 	$headers = $request->headers->all();
	// 	$proto   = $this->getProto();
	// 	// 请求外部接口
	// 	try {
	// 		$client   = new Client();
	// 		$response = $client->request('GET', config('outsideurls.AGGMLogin') . urlencode($proto));
	// 		$result   = (string) $response->getBody();
	// 		$str      = substr($result, strpos($result, '=') + 1);
	// 		$resp     = new S2C_AGLoginResult();
	// 		$resp->mergeFromString($str);
	// 		$res = $this->respToArray($resp);
	//
	// 		$firstPage        = 'home';
	// 		$res['firstPage'] = $firstPage;
	// 		$res['headers']   = $headers;
	// 		$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip && 保存用户等级
	// 		return $this->apiResponse->json($res);
	// 	} catch (\Exception $e) {
	// 		$this->apiResponse->error($e->getMessage(), 500);
	// 	}
	// }
	public function gmLogin(Request $request)
	{
		$headers = $request->headers->all();
		$oneCode = $request->input('oneCode');
		$toBind = $request->input('bind'); //判断是绑定 还是登陆
		$proto   = $this->getProto();
		// 请求外部接口
		try {
			$client   = new Client();
			$response = $client->request('GET', config('outsideurls.AGGMLogin') . urlencode($proto));
			$result   = (string) $response->getBody();
			$str      = substr($result, strpos($result, '=') + 1);
			$resp     = new S2C_AGLoginResult();
			$resp->mergeFromString($str);

			$res = $this->respToArray($resp);
			$firstPage        = 'home';
			$res['firstPage'] = $firstPage;
			$res['headers']   = $headers;
			if ($res && $res['szToken'] && $userName = $res['szUser']) {
				$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip && 保存用户等级

				// 账号密码验证成功  去验证 google身份验证器oneCode
				$userBindSecret = BindSecret::where('user_name', $userName)->pluck('secret')->first();
				// 用户已经绑定过google验证器秘钥
				if ($userBindSecret) {
					$loginSuccess = $this->checkGoogleOneCode($userBindSecret, $oneCode);
					if ($loginSuccess) {
						return $this->apiResponse->json($res);
					}
					else {
						$this->apiResponse->error('google验证码有误！', 401);
					}
				}
				else {
					// 用户未绑定google秘钥
					// 查询redis中是否存在秘钥
					$redisSecret = Redis::get($userName . ':google_secret:');
					// 存在秘钥
					if ($redisSecret) {
						// 如果是绑定
						if($toBind){
							$canBind = $this->checkGoogleOneCode($redisSecret, $oneCode);
							if ($canBind) {
								BindSecret::create(['user_name' => $userName, 'secret' => $redisSecret]);
								Redis::del($userName . ':google_secret:');
								return $this->apiResponse->json(['msg'=>'绑定成功!请重新登陆!']);
							}
							else {
								$this->apiResponse->error('google验证码输入有误！', 401);
							}
						}else{
							// 不是绑定 但秘钥在redis中已存在 说明用户没有点击绑定按钮 重新弹窗二维码(使用存在的secret)
							$qrCode =  $this->createQrCode($userName,$redisSecret,$this->host); // svg图片字符串
							return $this->apiResponse->json(compact('qrCode'));
						}
					}
					else {
						// 不存在则 创建secret并且返回二维码
						$ga     = new PHPGangsta_GoogleAuthenticator();
						$secret = $ga->createSecret();
						Redis::set($userName . ':google_secret:', $secret);
						$qrCode =  $this->createQrCode($userName,$secret,$this->host); // svg图片字符串
						return $this->apiResponse->json(compact('qrCode'));
					}
				}
			}else{
				$this->apiResponse->error('账号或者密码有误!', 400);
			}

		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}
	/**
	 * agent 代理后台登陆接口
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function agLogin(Request $request)
	{
//		$headers = $request->headers->all();
//		$proto   = $this->getProto();
//		// 请求外部接口
//		try {
//			$client   = new Client();
//			$response = $client->request('GET', config('outsideurls.AGLogin') . urlencode($proto));
//			$result   = (string) $response->getBody();
//			$str      = substr($result, strpos($result, '=') + 1);
//			$resp     = new S2C_AGLoginResult();
//			$resp->mergeFromString($str);
//			$res              = $this->respToArray($resp);
//			$firstPage        = 'home';
//			$res['firstPage'] = $firstPage;
//			$res['headers']   = $headers;
//			$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip
//			Redis::setex($res['szToken'] . '_agent', 36000, 1); //
//			Redis::setex($res['szToken'] . ':agent_id:', 36000, $res['szUser']); // 存放代理id
//			return $this->apiResponse->json($res);
//		} catch (\Exception $e) {
//			$this->apiResponse->error($e->getMessage(), 500);
//		}

        $headers = $request->headers->all();
        $oneCode = $request->input('oneCode');
        $toBind = $request->input('bind'); //判断是绑定 还是登陆
        $proto   = $this->getProto();
        // 请求外部接口
        try {
            $client   = new Client();
            $response = $client->request('GET', config('outsideurls.AGLogin') . urlencode($proto));
            $result   = (string) $response->getBody();
            $str      = substr($result, strpos($result, '=') + 1);
            $resp     = new S2C_AGLoginResult();
            $resp->mergeFromString($str);

            $res = $this->respToArray($resp);
            $firstPage        = 'home';
            $res['firstPage'] = $firstPage;
            $res['headers']   = $headers;
            if ($res && $res['szToken'] && $userName = $res['szUser']) {

                // 账号密码验证成功  去验证 google身份验证器oneCode
                $userBindSecret = BindSecret::where('user_name', $userName)->pluck('secret')->first();
                // 用户已经绑定过google验证器秘钥
                if ($userBindSecret) {
                    $loginSuccess = $this->checkGoogleOneCode($userBindSecret, $oneCode);
                    if ($loginSuccess) {
                        $this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip
                        Redis::setex($res['szToken'] . '_agent', 36000, 1); //
                        Redis::setex($res['szToken'] . ':agent_id:', 36000, $res['szUser']); // 存放代理id
                        return $this->apiResponse->json($res);
                    }
                    else {
                        $this->apiResponse->error('google验证码有误！', 401);
                    }
                }
                else {
                    // 用户未绑定google秘钥
                    // 查询redis中是否存在秘钥
                    $redisSecret = Redis::get($userName . ':google_secret:');
                    // 存在秘钥
                    if ($redisSecret) {
                        // 如果是绑定
                        if($toBind){
                            $canBind = $this->checkGoogleOneCode($redisSecret, $oneCode);
                            if ($canBind) {
                                BindSecret::create(['user_name' => $userName, 'secret' => $redisSecret]);
                                Redis::del($userName . ':google_secret:');
                                return $this->apiResponse->json(['msg'=>'绑定成功!请重新登陆!']);
                            }
                            else {
                                $this->apiResponse->error('google验证码输入有误！', 401);
                            }
                        }else{
                            // 不是绑定 但秘钥在redis中已存在 说明用户没有点击绑定按钮 重新弹窗二维码(使用存在的secret)
                            $qrCode =  $this->createQrCode($userName,$redisSecret,$this->host); // svg图片字符串
                            return $this->apiResponse->json(compact('qrCode'));
                        }
                    }
                    else {
                        // 不存在则 创建secret并且返回二维码
                        $ga     = new PHPGangsta_GoogleAuthenticator();
                        $secret = $ga->createSecret();
                        Redis::set($userName . ':google_secret:', $secret);
                        $qrCode =  $this->createQrCode($userName,$secret,$this->host); // svg图片字符串
                        return $this->apiResponse->json(compact('qrCode'));
                    }
                }
            }else{
                $this->apiResponse->error('账号或者密码有误!', 400);
            }

        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }

    }

	/**
	 * operate 运营后台登陆接口
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function agyyLogin(Request $request)
	{
		// $headers = $request->headers->all();
		// $proto   = $this->getProto();
		// // 请求外部接口
		// try {
		// 	$client   = new Client();
		// 	$response = $client->request('GET', config('outsideurls.AGYYLogin') . urlencode($proto));
		// 	$result   = (string) $response->getBody();
		// 	$str      = substr($result, strpos($result, '=') + 1);
		// 	$resp     = new S2C_AGLoginResult();
		// 	$resp->mergeFromString($str);
		// 	$res              = $this->respToArray($resp);
		// 	$firstPage        = 'home';
		// 	$res['firstPage'] = $firstPage;
		// 	$res['headers']   = $headers;
		// 	$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip
		// 	return $this->apiResponse->json($res);
		// } catch (\Exception $e) {
		// 	$this->apiResponse->error($e->getMessage(), 500);
		// }
		$headers = $request->headers->all();
		$oneCode = $request->input('oneCode');
		$toBind = $request->input('bind'); //判断是绑定 还是登陆
		$proto   = $this->getProto();
		// 请求外部接口
		try {
			$client   = new Client();
			$response = $client->request('GET', config('outsideurls.AGYYLogin') . urlencode($proto));
			$result   = (string) $response->getBody();
			$str      = substr($result, strpos($result, '=') + 1);
			$resp     = new S2C_AGLoginResult();
			$resp->mergeFromString($str);

			$res = $this->respToArray($resp);
			$firstPage        = 'home';
			$res['firstPage'] = $firstPage;
			$res['headers']   = $headers;
			if ($res && $res['szToken'] && $userName = $res['szUser']) {

				// 账号密码验证成功  去验证 google身份验证器oneCode
				$userBindSecret = BindSecret::where('user_name', $userName)->pluck('secret')->first();
				// 用户已经绑定过google验证器秘钥
				if ($userBindSecret) {
					$loginSuccess = $this->checkGoogleOneCode($userBindSecret, $oneCode);
					if ($loginSuccess) {
						$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip && 保存用户等级
						return $this->apiResponse->json($res);
					}
					else {
						$this->apiResponse->error('google验证码有误！', 401);
					}
				}
				else {
					// 用户未绑定google秘钥
					// 查询redis中是否存在秘钥
					$redisSecret = Redis::get($userName . ':google_secret:');
					// 存在秘钥
					if ($redisSecret) {
						// 如果是绑定
						if($toBind){
							$canBind = $this->checkGoogleOneCode($redisSecret, $oneCode);
							if ($canBind) {
								BindSecret::create(['user_name' => $userName, 'secret' => $redisSecret]);
								Redis::del($userName . ':google_secret:');
								return $this->apiResponse->json(['msg'=>'绑定成功!请重新登陆!']);
							}
							else {
								$this->apiResponse->error('google验证码输入有误！', 401);
							}
						}else{
							// 不是绑定 但秘钥在redis中已存在 说明用户没有点击绑定按钮 重新弹窗二维码(使用存在的secret)
							$qrCode =  $this->createQrCode($userName,$redisSecret,$this->host); // svg图片字符串
							return $this->apiResponse->json(compact('qrCode'));
						}
					}
					else {
						// 不存在则 创建secret并且返回二维码
						$ga     = new PHPGangsta_GoogleAuthenticator();
						$secret = $ga->createSecret();
						Redis::set($userName . ':google_secret:', $secret);
						$qrCode =  $this->createQrCode($userName,$secret,$this->host); // svg图片字符串
						return $this->apiResponse->json(compact('qrCode'));
					}
				}
			}else{
				$this->apiResponse->error('账号或者密码有误!', 400);
			}

		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * service 客服后台登陆接口
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function agkfLogin(Request $request)
	{
		$headers = $request->headers->all();
		$proto   = $this->getProto(true);
		// 请求外部接口
		try {
			$client   = new Client();
			$response = $client->request('GET', config('outsideurls.AGKFLogin') . urlencode($proto));
			$result   = (string) $response->getBody();
			$str      = substr($result, strpos($result, '=') + 1);
			$resp     = new S2C_AGLoginResult();
			$resp->mergeFromString($str);
			$res              = $this->respToArray($resp);
			$firstPage        = 'home';
			$res['firstPage'] = $firstPage;
			$res['headers']   = $headers;
			$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip
			Redis::setex($res['szToken'] . '_service_id', 36000, $res['szUser']); //设置客服token 对应的 service_id 用于客服聊天绑定时
			return $this->apiResponse->json($res);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}



		// $headers = $request->headers->all();
		// $oneCode = $request->input('oneCode');
		// $toBind = $request->input('bind'); //判断是绑定 还是登陆
		// $proto   = $this->getProto();
		// // 请求外部接口
		// try {
		// 	$client   = new Client();
		// 	$response = $client->request('GET', config('outsideurls.AGKFLogin') . urlencode($proto));
		// 	$result   = (string) $response->getBody();
		// 	$str      = substr($result, strpos($result, '=') + 1);
		// 	$resp     = new S2C_AGLoginResult();
		// 	$resp->mergeFromString($str);
		//
		// 	$res = $this->respToArray($resp);
		// 	$firstPage        = 'home';
		// 	$res['firstPage'] = $firstPage;
		// 	$res['headers']   = $headers;
		// 	if ($res && $res['szToken'] && $userName = $res['szUser']) {
		//
		// 		// 账号密码验证成功  去验证 google身份验证器oneCode
		// 		$userBindSecret = BindSecret::where('user_name', $userName)->pluck('secret')->first();
		// 		// 用户已经绑定过google验证器秘钥
		// 		if ($userBindSecret) {
		// 			$loginSuccess = $this->checkGoogleOneCode($userBindSecret, $oneCode);
		// 			if ($loginSuccess) {
		// 				$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip && 保存用户等级
		// 				Redis::setex($res['szToken'] . '_service_id', 36000, $res['szUser']); //设置客服token 对应的 service_id 用于客服聊天绑定时
		//
		// 				return $this->apiResponse->json($res);
		// 			}
		// 			else {
		// 				$this->apiResponse->error('google验证码有误！', 401);
		// 			}
		// 		}
		// 		else {
		// 			// 用户未绑定google秘钥
		// 			// 查询redis中是否存在秘钥
		// 			$redisSecret = Redis::get($userName . ':google_secret:');
		// 			// 存在秘钥
		// 			if ($redisSecret) {
		// 				// 如果是绑定
		// 				if($toBind){
		// 					$canBind = $this->checkGoogleOneCode($redisSecret, $oneCode);
		// 					if ($canBind) {
		// 						BindSecret::create(['user_name' => $userName, 'secret' => $redisSecret]);
		// 						Redis::del($userName . ':google_secret:');
		// 						return $this->apiResponse->json(['msg'=>'绑定成功!请重新登陆!']);
		// 					}
		// 					else {
		// 						$this->apiResponse->error('google验证码输入有误！', 401);
		// 					}
		// 				}else{
		// 					// 不是绑定 但秘钥在redis中已存在 说明用户没有点击绑定按钮 重新弹窗二维码(使用存在的secret)
		// 					$qrCode =  $this->createQrCode($userName,$redisSecret,$this->host); // svg图片字符串
		// 					return $this->apiResponse->json(compact('qrCode'));
		// 				}
		// 			}
		// 			else {
		// 				// 不存在则 创建secret并且返回二维码
		// 				$ga     = new PHPGangsta_GoogleAuthenticator();
		// 				$secret = $ga->createSecret();
		// 				Redis::set($userName . ':google_secret:', $secret);
		// 				$qrCode =  $this->createQrCode($userName,$secret,$this->host); // svg图片字符串
		// 				return $this->apiResponse->json(compact('qrCode'));
		// 			}
		// 		}
		// 	}else{
		// 		$this->apiResponse->error('账号或者密码有误!', 400);
		// 	}
		//
		// } catch (\Exception $e) {
		// 	\Log::info($e);
		// 	$this->apiResponse->error($e->getMessage(), 500);
		// }

	}

	/**
	 *玩家登陆接口
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function agwjLogin(Request $request)
	{

//		$headers = $request->headers->all();
        $proto   = $this->getProto(true);

		// 请求外部接口
		try {
			$client   = new Client();
			$response = $client->request('GET', config('outsideurls.AGWJLogin') . urlencode($proto));
			$result   = (string) $response->getBody();
			$str      = substr($result, strpos($result, '=') + 1);
			$resp     = new S2C_AGLoginResult();
			$resp->mergeFromString($str);
			$res              = $this->respToArray($resp);
			$firstPage        = 'home';
			$res['firstPage'] = $firstPage;
//			$res['headers']   = $headers;
//			$this->setTokenIp($request, $res['szToken'], $res['nLv']); //设置登录用户的token 对应的 访问ip
			return $this->apiResponse->json($res);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	public function wjLoginCode(Request $request)
	{
		$userName = $request->input('username');
		// 请求外部接口
		try {
			$client   = new Client();
			$response = $client->request('GET', config('outsideurls.SendSMS') . $userName);
			$result   = (string) $response->getBody();
	       return $this->apiResponse->json(compact('result'));
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}


	/**
	 * 设置 token => ip into redis  token_nlv => $nLv
	 * @param Request $request
	 * @param         $szToken
	 * @param         $nLv
	 */
	private function setTokenIp(Request $request, $szToken, $nLv)
	{
		$ip = $request->header('x-real-ip');
		Redis::setex($szToken, 36000, $ip); // 3600秒 1个小时
		Redis::setex($szToken . '_nlv', 36000, $nLv); //
	}

	/**
	 * @param bool $flag
	 * @return string
	 */
	private function getProto($flag=false)
	{
		$loginMessage  = new C2S_AGLogin();
		$szUser        = request('username');//用戶名，eg：admin
		$szPwd         = request('password');//密碼，eg：123456
		$szTime        = date('Y-m-d H:i:s');//时间戳，eg：2018-1-1，客户端时间必须与北京时间一致，否则登录无效
		$szMachineCode = '123456';//机器码，eg：123456
		$szMd5         = md5($szUser . $szPwd . $szTime . $szMachineCode . 'FASD23DS');//MD5（szUser+szPwd+szTime+szMachineCode+盐）
		$code          = request('code'); //验证码
		if($flag){
			$uuid          = request('uuid'); // 前端显示获取图片验证码时发送的唯一id
			$this->checkCaptcha($uuid, $code); //验证码校验
		}

		$szCode = request()->header('x-real-ip');//传入玩家的IP

        $loginMessage->setSzUser($szUser);
		$loginMessage->setSzPwd($szPwd);
		$loginMessage->setSzTime($szTime);
		$loginMessage->setSzMachineCode($szMachineCode);
		$loginMessage->setSzMd5($szMd5);
		$loginMessage->setSzCode($szCode);
		return $loginMessage->serializeToString();
	}

	/**
	 * 检查用户输入的验证码
	 * @param $uuid
	 * @param $szCode
	 */
	private function checkCaptcha($uuid, $szCode)
	{
		$code = Redis::get($uuid);// 获取redis
		if (!$code) {
			$this->apiResponse->error('验证码已过期！');
		}
		if (strcasecmp($code, $szCode) !== 0) {
			$this->apiResponse->error('验证码有误！');
		}
	}

	/**
	 * @param $resp
	 * @return mixed
	 */
	private function respToArray($resp)
	{
		$res['szUser']     = $resp->getSzUser();
		$res['nLv']        = $resp->getNLv();
		$res['nPercent']   = $resp->getNPercent();
		$res['szBindID']   = $resp->getSzBindID();
		$res['fCurMoney']  = $resp->getFCurMoney();
		$res['bankMoney']  = $resp->getBankMoney();
		$res['szToken']    = $resp->getSzToken();
		$res['szNickName'] = $resp->getSzNickName();
		$res['szAliPay']   = $resp->getSzAliPay();
		$res['szRealName'] = $resp->getSzRealName();
		return $res;
	}

	/**
	 * 验证google 秘钥 与 oneCode 是否正确
	 * @param $secret
	 * @param $oneCode
	 * @return bool
	 */
	private function checkGoogleOneCode($secret, $oneCode)
	{
		$ga          = new PHPGangsta_GoogleAuthenticator();
		$checkResult = $ga->verifyCode($secret, $oneCode, 0);
		return $checkResult;
	}

	/**
	 * 创建二维码 返回 svg 字符串
	 * @param      $userName
	 * @param      $secret
	 * @param null $title
	 * @return mixed
	 */
	private function createQrCode($userName,$secret,$title = null)
	{
		$url    = 'otpauth://totp/' . $userName . '?secret=' . $secret;
		if (isset($title)) {
			$url .= '&issuer='.$title;
		}
		$qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->color(237, 63, 20)->size(180)->margin(2)->generate($url);
		return $qrCode;
		// return response($qrCode)->header('Content-type', 'image/jpeg');
	}
}
