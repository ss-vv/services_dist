<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class CheckTokenIp
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
		$ip    = $request->header('x-real-ip');
		$token = $request->header('Authorization');

		if (!$token) {
			$message = 'Forbidden!';
			return new Response(compact('message'), 403);
		}

        $httpHost = $request->getHttpHost();
		$noCheckHosts = ['daili.eilkli.com','daili.lxmake.com'];
		if(in_array($httpHost,$noCheckHosts)){
            return $next($request);
        }

		$tokenIp = Redis::get($token);
		if ( null !== $ip && $ip !== $tokenIp) {
			$message = '登录超时!';
			return new Response(compact('message','ip','tokenIp'), 401);
		}
		return $next($request);
	}
}
