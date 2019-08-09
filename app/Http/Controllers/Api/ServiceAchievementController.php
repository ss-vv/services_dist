<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServiceAchievementResource;
use App\Models\ServiceAchievement;


class ServiceAchievementController extends ApiController
{

	protected $serviceAchievement;

	public function __construct(ServiceAchievement $serviceAchievement)
	{
		parent::__construct();
		$this->serviceAchievement = $serviceAchievement;
	}

	/**
	 * 查询客服业绩
	 */
	public function index()
	{
		$serviceId     = request('service_id');
		$perPage = request('per_page',10);
		$serviceAchievements = $this->serviceAchievement->when($serviceId, function ($query) use ($serviceId) {
			return $query->where('service_id',$serviceId);
		})->paginate($perPage);
		return $this->apiResponse->paginator($serviceAchievements, ServiceAchievementResource::class);
	}

}