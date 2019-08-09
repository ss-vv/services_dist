<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SuggestionResource;
use App\Models\Suggestion;
use Illuminate\Http\Request;


class SuggestionController extends ApiController
{
	protected $suggestion;

	public function __construct(Suggestion $suggestion)
	{
		parent::__construct();
		$this->suggestion   = $suggestion;

	}


	public function store(Request $request)
	{
        $inputs = $request->all();
        $this->validateRequest(
            $request,
            [
                'mobile' => 'nullable', //手机号
                'content' => 'required', //意见建议
                'width' => 'required', //屏幕宽度
                'height' => 'required', //屏幕高度
                'renderer' => 'required', //显卡渲染器
                'vendor' => 'required', //显卡供应商
                'device_pixel_ratio' => 'required', //设备像素比
                'platform' => 'required', //硬件平台
                'appCodeName' => 'required', //浏览器代号
                'appName' => 'required', //浏览器名称
                'appVersion' => 'required', //版本信息
            ]
        );
        $inputs['ip'] = $this->getClientIp(); // 微信浏览器使用 $request->getClientIp() ip不对

        $this->suggestion->fill($inputs)->save();

        return $this->apiResponse->created();
	}


    private function getClientIp()
    {

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            } else {
                if (getenv('HTTP_X_FORWARDED')) {
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                } else {
                    if (getenv('HTTP_FORWARDED_FOR')) {
                        $ipaddress = getenv('HTTP_FORWARDED_FOR');
                    } else {
                        if (getenv('HTTP_FORWARDED')) {
                            $ipaddress = getenv('HTTP_FORWARDED');
                        } else {
                            if (getenv('REMOTE_ADDR')) {
                                $ipaddress = getenv('REMOTE_ADDR');
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }

        return $ipaddress;
    }

    /**
     * 查询反馈意见
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $perPage = request('per_page',20);
        $playerSummaries = $this->suggestion->orderByDesc('id')->paginate($perPage);
        return $this->apiResponse->paginator($playerSummaries, SuggestionResource::class);
    }
}