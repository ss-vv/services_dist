<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Validator;

/**
 * 基础Controller
 * Class ApiController
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller
{
	protected $apiResponse;

	// use Helper;

	/**
	 * ApiController constructor.
	 */
	public function __construct()
	{
		$this->apiResponse = new ApiResponse();
	}

	protected function validateRequest(Request $request, array $rules)
	{
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// $errorMessages = $validator->errors()->messages();
			// foreach ($errorMessages as $key => $value) {
			// 	$errorMessages[$key] = $value[0];
			// }
			$errorMessages = $validator->errors()->first();
			$this->apiResponse->error($errorMessages,400);
		}
	}

}