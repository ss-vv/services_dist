<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use GatewayClient\Gateway;
use App\Http\Resources\WithdrawalResource;
use App\Models\Withdrawal;
use App\Models\PunchCard;
use App\Models\Merchant;
use App\Models\WithdrawalServiceTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class WithdrawalController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 银行卡提现申请
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
        $sign = $request->query('sign');    // 签名串
        $time = $request->query('time');    // 时间戳
        $json = $request->getContent();     // json请求体
//        $json = str_replace(' ','',$json);  // 删除curl测试请求产生的空白字符
//        $json = '{
//                    "merchant_id": "5d49145f1d008",
//                     "order_id": "'.time().'",
//                     "notify_url": "http://self.example.com/callback_url",
//                     "bill_price": "100.55",
//                     "extra": "extra  info",
//                     "info": {
//                             "user_id": "user_001",
//                             "device_ip": "1.2.3.4",
//                             "device_id": "D3K9K78J4 ",
//                             "name": "张三",
//                             "bank_card": "11112222333344455",
//                             "bank_code": "ICBC",
//                             "tel": "13912345678",
//                             "device_type": "android"
//                        }
//                   }';

        $aData = json_decode($json,true);
        if (empty($aData))
        {
            return response()->json(['status'=>30000,'message'=>'无法解析请求数据','flashid'=>null]);
        }
        $merchant_order_id = $aData['order_id'];     // 商户订单号

        $oMerchant = Merchant::where('merchant_no',$aData['merchant_id'])->first();
        if (!is_object($oMerchant))
        {
            return response()->json(['status'=>30000,'message'=>'不存在该商户','flashid'=>$merchant_order_id]);
        }
        $token = $oMerchant->token;         // 商户token

        // 验证签名
        if ($sign != md5($json . $time . $token))
        {
            return response()->json(['status'=>10106,'message'=>'签名错误','flashid'=>$merchant_order_id]);
        }
        $service_id = '0'; // 接收人为0 表示没有客服收到信息
        $todayStart = Carbon::today()->toDateTimeString();
        // 当前上班的客服
        $onServiceIds = WithdrawalServiceTime::where('status', 1)->where('on_time', '>=', $todayStart)->get(['service_id']);
        if ($onServiceIds->isNotEmpty())
        {
            foreach ($onServiceIds as $one)
            {
                $aServiceIds[] = $one->service_id;      // 当前上班的客服
            }
            //有分配订单的客服
            $oRes = Withdrawal::select(DB::raw('service_id, count(*) as nums'))
                                        ->whereIn('service_id', $aServiceIds)
                                        ->where('status', '<=',  Withdrawal::STATUS_HOLDING)
                                        ->where('created_at', '>=', $todayStart)
                                        ->groupBy('service_id')
                                        ->orderBy('nums','ASC')
                                        ->get();
            if($oRes->isNotEmpty())
            {
                foreach ($oRes as $v)
                {
                    $aServiceIds2[] = $v->service_id;           // 有分配订单的客服
                }
                $aDiff = array_diff($aServiceIds,$aServiceIds2);// $aServiceIds2元素 <= $aServiceIds元素 所以取出的是还未分配的客服
                if(!empty($aDiff))
                {
                    $service_id = array_random($aDiff);         // 随机分配一个未分配订单的上班客服
                }
                else  $service_id = $aServiceIds2[0];           // 取订单数分配最少的第一个客服
            }
            else $service_id = array_random($aServiceIds);      // 随机分配一个上班的客服
        }

        $order_id = date('YmdHis').mt_rand(10000,99999);        // 平台订单号2019080616074012345
        $oWithdrawal = Withdrawal::firstOrCreate(['merchant_id' => $oMerchant->id,'merchant_order_id' => $merchant_order_id], [
            'order_id'      => $order_id,
            'bill_price'    => $aData['bill_price'],
            'user_id'       => $aData['info']['user_id'],
            'name'          => $aData['info']['name'],
            'bank_card'     => $aData['info']['bank_card'],
            'bank_code'     => $aData['info']['bank_code'],
            'notify_url'    => $aData['notify_url'],
            'tel'           => $aData['info']['tel'],
            'device_ip'     => $aData['info']['device_ip'],
            'device_type'   => $aData['info']['device_type'],
            'device_id'     => $aData['info']['device_id'],
            'extra'         => $aData['extra'],
            'service_id'    => $service_id,
        ]);
        if($oWithdrawal->wasRecentlyCreated)    //订单创建成功
        {
            // 推送消息给上班的客服
            if ($service_id)
            {
                Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
                Gateway::sendToUid($service_id, json_encode($oWithdrawal));
            }
            return response()->json(['status'=>10000,'message'=>'请求成功','flashid'=>$merchant_order_id]);
        }
        else
        {
            return response()->json(['status'=>10107,'message'=>'订单已经存在','flashid'=>$merchant_order_id]);
        }

    }

    /**
     * 查询提现申请记录
     */
    public function getList()
    {
        $perPage = request('per_page',10);
        $user_id = request('user_id');
        $token   = request()->header('Authorization');
        $serviceId = Redis::get($token . '_service_id');
        if(!$serviceId){
            $this->apiResponse->error('登录超时!',401);
        }

        $query = Withdrawal::select(['withdrawals.*','merchants.merchant_no'])->where('service_id',$serviceId)->where('status', '<=', Withdrawal::STATUS_HOLDING)
                            ->orWhere('holder',$serviceId)->where('status', '<=', Withdrawal::STATUS_HOLDING);
        if($user_id){
            $query = $query->where('user_id',$user_id);
        }

        $withdrawals = $query->join('merchants', 'merchant_id', 'merchants.id')->orderBy('withdrawals.id', 'desc')->paginate($perPage);
        return $this->apiResponse->paginator($withdrawals, WithdrawalResource::class);
    }

    /**
     * 修改提现订单状态
     */
    public function updateStatus()
    {
        $id     = request('id');
        $status = request('status');
        if(empty($id) || empty($status))
        {
            return response()->json(['status'=>0,'msg'=>'参数错误']);
        }
        $oWithdrawal = Withdrawal::find($id);
        if(!is_object($oWithdrawal))
        {
            return response()->json(['status'=>0,'msg'=>'记录不存在']);
        }
        $token  = request()->header('Authorization');
        $serviceId = Redis::get($token . '_service_id');

        $now = Carbon::now()->toDateTimeString();
        if ($status == Withdrawal::STATUS_HOLDING)   //修改状态 0 => 1
        {
            $bRes = $oWithdrawal->update(['status'=>$status,'holder'=>$serviceId,'updated_at'=>$now]);
            if($bRes)
            {
                return response()->json(['status'=>1,'msg'=>'操作成功']);
            }
            else
            {
                return response()->json(['status'=>0,'msg'=>'数据更新异常']);
            }
        }
        else    //修改状态 1 => 2或3
        {
            if($status == Withdrawal::STATUS_SUCCESS)
            {
                $res = 10000;
                $message = '出款成功';
            }
            else
            {
                $res = 20000;
                $message = '订单被拒绝';
            }
            $sToken = $oWithdrawal->merchant->token;  // 商户token
            $post = [
                'flashid'       => $oWithdrawal->order_id,
                'merchant'      => $oWithdrawal->merchant->merchant_no,
                'status'        => $res,
                'payed_money'   => $oWithdrawal->bill_price,
                'payed_time'    => $now,
                'message'       => $message,
                'merchant_order_id' => $oWithdrawal->merchant_order_id,
            ];
            $sign = md5(json_encode($post) . time() . $sToken);
            $url = $oWithdrawal->notify_url.'?time='.time().'&sign='.$sign;
            $iTime = time() - strtotime($oWithdrawal->updated_at);
            $bRes = $oWithdrawal->update(['status'=>$status,'process_time'=>$iTime,'updated_at'=>$now]);
            if (!$bRes)
            {
                return response()->json(['status'=>0,'msg'=>'数据更新异常']);
            }
            try{
                $client = new Client();
                $response = $client->request('POST', $url, [    //发送json形式的POST请求
                    'json' => $post,
                ]);
                if ($response->getStatusCode() == '200') {
                    //更新回调通知状态
                    $now = Carbon::now()->toDateTimeString();

                    $contents = $response->getBody()->getContents();
                    $contents = json_decode($contents,true);
                    if($contents['status'] == 0)
                    {
                        Withdrawal::where('id',$id)->update(['notify_status'=>Withdrawal::NOTIFY_SUCCESS,'updated_at'=>$now]);
                        return response()->json(['status'=>1,'msg'=>'操作成功']);
                    }
                    else
                    {
                        Withdrawal::where('id',$id)->update(['notify_status'=>Withdrawal::NOTIFY_FAILURE,'updated_at'=>$now]);
                        return response()->json(['status'=>0,'msg'=>'回调通知失败']);
                    }
                }
            }
            catch (Exception $e)
            {
                return response()->json(['status'=>0,'msg'=>'网络请求异常']);
            }
        }
    }


	/**
	 * 客服打卡
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->all();
        $now = Carbon::now()->toDateTimeString();
        $status = array_get($inputs, 'status');
        $token     = request()->header('Authorization');
        $serviceId = Redis::get($token . '_service_id');
        if (!$serviceId) {
            $this->apiResponse->error('登录超时!', 401);
        }
        $todayStart = Carbon::today()->toDateTimeString();
		$todayEnd   = Carbon::tomorrow()->toDateTimeString();
		$lastOne    = PunchCard::where('service_id', $serviceId)->where('punch_time', '>=', $todayStart)->where('punch_time', '<', $todayEnd)->latest()->first();
		$lastStatus = array_get($lastOne, 'status');
		// 如果 (最后一条记录存在 并且记录状态和添加的状态一致)  或者 (记录不存在 但是添加状态却是下班打卡)
		if (($lastStatus && $lastStatus == $status) || (!$lastStatus && $status == 2)) {
			// 请求有误
			$this->apiResponse->errorForbidden();
		}
		DB::beginTransaction();
		try {
            $oPunchCard    = new PunchCard();
            $oWithdrawalServiceTime = new WithdrawalServiceTime();
            // 上次记录是上班
			if ($lastStatus == 1) {
				// 创建下班记录
                $oPunchCard->fill([
					'service_time_id' => array_get($lastOne, 'service_time_id'),
					'service_id'      => $serviceId,
					'status'          => $status,
					'type'            => array_get($inputs, 'type'),
					'punch_time'      => $now,
				])->save();
				// 更新 service_manages中 记录的 下班时间
                $oWithdrawalServiceTime->where('id', array_get($lastOne, 'service_time_id'))
					->update([
						'status'   => $status,
						'off_time' => $now,
					]);
                // 重置已分配给当前客服的出款订单service_id => '0'
                Withdrawal::where('service_id', $serviceId)->where('status', Withdrawal::STATUS_WAITING)->where('created_at', '>=', $todayStart)
                    ->update([
                        'service_id' => '0',
                        'updated_at' => $now,
                    ]);
            }
			else {
				// 上次记录是下班 或者 上次记录不存在
                $oWithdrawalServiceTime->fill([
					'service_id' => $serviceId,
					'status'     => $status,
					'on_time'    => $now,
				])->save();

				// 创建上班记录
                $oPunchCard->fill([
					'service_time_id' => $oWithdrawalServiceTime->id,
					'service_id'      => $serviceId,
					'status'          => $status,
					'type'            => array_get($inputs, 'type'),
					'punch_time'      => $now,
				])->save();
				// 上班时调用一下 客服分配
				Artisan::call('Withdrawal:assignService');
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			$this->apiResponse->errorInternal();
		}
		return $this->apiResponse->created();
	}


    /**
     * 查询所有提现申请记录
     */
    public function getAllList()
    {
        $perPage = request('per_page',20);
        $user_id = request('user_id');

        $query = Withdrawal::select(['withdrawals.*','merchants.merchant_no'])->latest();
        if($user_id){
            $query = $query->where('user_id',$user_id);
        }

        $withdrawals = $query->join('merchants', 'merchant_id', 'merchants.id')->paginate($perPage);
        return $this->apiResponse->paginator($withdrawals, WithdrawalResource::class);
    }

    /**
     * 获取客服出款统计列表
     */
    public function getStatList()
    {
        $startTime = request('start_time');
        $endTime   = request('end_time');
        $query = Withdrawal::select('service_id');

        if (!empty($startTime))
        {
            $startTime = date('Y-m-d H:i:s',strtotime($startTime));
            $query = $query->where('created_at', '>=', $startTime);
        }
        if (!empty($endTime))
        {
            $endTime = date('Y-m-d H:i:s',strtotime($endTime));
            $query = $query->where('created_at', '<=', $endTime);
        }
        $oServiceIds = $query->groupBy('service_id')->get();

        $data = [];
        $k = 0;
        foreach ($oServiceIds as $one)
        {
            $service_id = $one->service_id;
            if(empty($service_id)) continue;
            $data[$k]['service_id'] = $service_id;
            $oServiceTime = WithdrawalServiceTime::where('service_id',$service_id)->where('status',1)->latest()->first(['on_time']);
            if (is_object($oServiceTime))
            {
                $data[$k]['on_time'] = $oServiceTime->on_time;
            }
            else $data[$k]['on_time'] = '还未上班';
            $query = Withdrawal::select(DB::raw('status, count(*) as nums'))->where('service_id',$service_id);
            if (!empty($startTime))
            {
                $query = $query->where('created_at', '>=', $startTime);
            }
            if (!empty($endTime))
            {
                $query = $query->where('created_at', '<=', $endTime);
            }

            $oWithdrawalStat = $query->groupBy('status')->get();
            $data[$k]['success'] = 0;
            $data[$k]['deny']    = 0;
            $data[$k]['holding'] = 0;
            $data[$k]['waiting'] = 0;
            foreach ($oWithdrawalStat as $item)
            {
                switch ($item->status)
                {
                    case Withdrawal::STATUS_SUCCESS:
                        $status = 'success';
                        break;
                    case Withdrawal::STATUS_DENY:
                        $status = 'deny';
                        break;
                    case Withdrawal::STATUS_HOLDING:
                        $status = 'holding';
                        break;
                    case Withdrawal::STATUS_WAITING:
                        $status = 'waiting';
                        break;
                    default:
                        $status = 'waiting';
                }
                $data[$k][$status] = $item->nums;
            }
            $query = Withdrawal::where('service_id',$service_id)->where('status','>', Withdrawal::STATUS_HOLDING);
            if (!empty($startTime))
            {
                $query = $query->where('created_at', '>=', $startTime);
            }
            if (!empty($endTime))
            {
                $query = $query->where('created_at', '<=', $endTime);
            }
            $process_time = $query->avg('process_time');

            $data[$k]['process_time'] = $this->showReadableTime($process_time);
            $k++;
        }
        return response()->json(['data'=>$data]);
    }

    /**
     * @param int $process_time
     * @return string 可读的时间
     */
    private function showReadableTime($process_time)
    {
        $process_time = intval($process_time);
        if(!$process_time)  return '';
        if ($process_time > 0 && $process_time < 60)
        {
            $time = $process_time.'秒';
        }
        elseif ($process_time >= 60 && $process_time < 3600)
        {
            $min = floor($process_time / 60).'分';
            $sec = $process_time % 60;
            if($sec)
            {
                $sec = $sec.'秒';
                $time = $min.$sec;
            }
            else $time = $min;
        }
        elseif ($process_time >= 3600)
        {
            $hour = floor($process_time / 3600).'时';
            $sec = $process_time % 3600;
            if($sec)
            {
                if($sec < 60)
                {
                    $time = $hour.$sec.'秒';
                }
                else
                {
                    $min = floor($sec / 60).'分';
                    $sec = $sec % 60;
                    if($sec)
                    {
                        $sec = $sec.'秒';
                        $time = $hour.$min.$sec;
                    }
                    else
                    {
                        $time = $hour.$min;
                    }
                }
            }
            else
            {
                $time = $hour;
            }
        }
        return $time;
    }

}