<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * 外部请求
 * Class RequestUrlController
 * @package App\Http\Controllers\Api
 */
class RequestUrlController extends ApiController
{

    public function toRequest(Request $request)
    {
        $url = $request->input('url');
        $type = $request->input('type');
        $paramsStr = $request->input('paramsStr');
        try {
            if ($type == 'form') {
                parse_str($paramsStr, $paramsArr);
                $data = [
                    'form_params' => $paramsArr,
                ];
            } elseif ($type == 'json') {
                $paramsArr = json_decode($paramsStr, true);
                $data = [
                    'json' => $paramsArr,
                ];
            } elseif ($type == 'form-data') {
                $paramsArr = json_decode($paramsStr, true);
                $multipart = [];
                foreach ($paramsArr as $key => $value) {
                    $multipart[] = ['name' => $key, 'contents' => $value];
                }
                $data = [
                    'multipart' => $multipart,
                ];
            } else {
                return response()->json(['message' => '请求类型错误']);
            }

            $client = new Client();
            $response = $client->request('POST', $url, $data);
            $code = $response->getStatusCode();
            $success = (string)$response->getBody();

            return response()->json(compact('code', 'success'));
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $success = false;

            return response()->json(compact('success', 'msg'));

        }

    }

}