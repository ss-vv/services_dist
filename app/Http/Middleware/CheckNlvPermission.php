<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class CheckNlvPermission
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//  当前请求路由别名
		$currentRouteName = $request->route()->getName();
		$nlvPermissionKey = $request->header('Authorization') . '_nlv'; // 登陆用户等级key
		$nlvAgentKey      = $request->header('Authorization') . '_agent';//是否是代理key
		$nlv              = Redis::get($nlvPermissionKey);
		$isAgent          = Redis::get($nlvAgentKey); //是否是代理
		$message = '你没有权限!';
		if (null !== $nlv) {
			if ($isAgent) {
				// 代理权限
				if (!array_get(config('nlvPermissions.agent'), $currentRouteName)) {
					return new Response(compact('message'), 403);
				}
				// nlv>=5的不可新增代理
				if ($nlv >= 5 && $currentRouteName === 'agent.insert_agent_account') {
					return new Response(compact('message'), 403);
				}
			}
			else {

				// admin 权限
				if ($nlv >= 0 && $nlv <= 9 || $nlv == 20) {
					if (!array_get(config('nlvPermissions.admin'), $currentRouteName)) {
						return new Response(compact('message'), 403);
					}
				}// operate 运营权限
				else if ($nlv >= 10 && $nlv <= 99) {
					if (!array_get(config('nlvPermissions.operate'), $currentRouteName)) {
						return new Response(compact('message'), 403);
					}
				}// service999 客服主管的权限
				else if ($nlv == 999) {
					if (!array_get(config('nlvPermissions.service999'), $currentRouteName)) {
						return new Response(compact('message'), 403);
					}
				}// service100 普通客服的权限
				else if ($nlv == 100) {
					if (!array_get(config('nlvPermissions.service100'), $currentRouteName)) {
						return new Response(compact('message'), 403);
					}
				}// service1000 审核客服的权限
				else if ($nlv == 1000) {
					if (!array_get(config('nlvPermissions.service1000'), $currentRouteName)) {
						return new Response(compact('message'), 403);
					}
				}
				else {
					return new Response(compact('message'), 403);
				}


			}
		}else{
			$message = '登录超时!';
			return new Response(compact('message'), 401);
		}

		return $next($request);
	}
}
