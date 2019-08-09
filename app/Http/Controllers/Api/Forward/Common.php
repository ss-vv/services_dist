<?php
namespace App\Http\Controllers\Api\Forward;

use GuzzleHttp\Client;

trait Common
{
	/**
	 * 返回请求外部接口的数据
	 * @param $proto  请求参数
	 * @param $url    请求url
	 * @param $resultClassName 请求返回数据解析类名
	 * @return mixed
	 */
	public function outerRespond($proto,$url,$resultClassName)
	{
		$client = new Client();
		$response = $client->request('GET', $url . urlencode($proto));
		$result   = (string) $response->getBody();
		$str      = substr($result, strpos($result, '=') + 1);
		$resp     = new $resultClassName();
		$resp->mergeFromString($str);
		$data = json_decode($resp->serializeToJsonString(),true);
		return $data;
	}
}