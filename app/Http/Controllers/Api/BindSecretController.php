<?php

namespace App\Http\Controllers\Api;

use App\Models\BindSecret;

class BindSecretController extends ApiController
{

	protected $bindSecret;

	public function __construct(BindSecret $bindSecret)
	{
		parent::__construct();
	    $this->bindSecret = $bindSecret;
	}

	/**
	 * 删除账号绑定的秘钥
	 */
	public function resetSecret()
	{
         $userName = request('user_name');
         if($userName!==null){
         	$this->bindSecret->where('user_name', $userName)->delete();
         	return $this->apiResponse->noContent();
         }
		 $this->apiResponse->error('参数有误');
	}

}