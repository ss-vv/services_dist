<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiResponse
{
	/**
	 * Respond with a created response and associate a location if provided.
	 *
	 * @param null|string $location
	 * @param null        $content
	 * @return Response
	 */
	public function created($location = null, $content = null)
	{
		$response = new Response($content);
		// 201
		$response->setStatusCode(Response::HTTP_CREATED);
		if (!is_null($location)) {
			$response->header('Location', $location);
		}

		return $response;
	}

	/**
	 * Respond with an accepted response and associate a location and/or content if provided.
	 *
	 * @param null|string $location
	 * @param mixed       $content
	 *
	 * @return Response
	 */
	public function accepted($location = null, $content = null)
	{
		$response = new Response($content);
		// 202
		$response->setStatusCode(Response::HTTP_ACCEPTED);

		if (!is_null($location)) {
			$response->header('Location', $location);
		}

		return $response;
	}

	/**
	 * Respond with a no content response.
	 *
	 * @return Response
	 */
	public function noContent()
	{
		$response = new Response(null);
		// 204
		return $response->setStatusCode(Response::HTTP_NO_CONTENT);
	}

	/**
	 * Return a json response.
	 * @param array $data
	 * @param array $headers
	 * @return Response
	 */
	public function json($data = [], array $headers = [])
	{
		return new Response(compact('data'), Response::HTTP_OK, $headers);
	}

	/**
	 *  Bind an item to a apiResource and start building a response.
	 * @param       $data
	 * @param       $resourceClass
	 * @param array $meta
	 * @return mixed
	 */
	public function item($data, $resourceClass, $meta = [])
	{
		if (count($meta)) {
			return (new $resourceClass($data))->additional($meta);
		}
		return new $resourceClass($data);
	}

	/**
	 * Bind a collection to a apiResource and start building a response.
	 *
	 * @param       $data
	 * @param       $resourceClass
	 * @param array $meta
	 * @return Response
	 */
	public function collection($data, $resourceClass, $meta = [])
	{
		if (count($meta)) {
			return $resourceClass::collection($data)->additional($meta);
		}
		return $resourceClass::collection($data);
	}


	/**
	 * Bind a paginator to a apiResource and start building a response.
	 *
	 * @param Paginator $paginator
	 * @param           $resourceClass
	 * @param array     $meta
	 * @return Response
	 */
	public function paginator(Paginator $paginator, $resourceClass, array $meta = [])
	{
		return $this->collection($paginator, $resourceClass, $meta);
	}

	/**
	 * Return an error response.
	 *
	 * @param string $message
	 * @param        $statusCode
	 * @return void
	 */
	public function error($message, $statusCode = 400)
	{
		// return new Response(compact('message','status_code'),$status_code,$header);
		throw new HttpException($statusCode, $message);
	}

	/**
	 * Return a 404 not found error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorNotFound($message = 'Not Found')
	{
		// 404
		$this->error($message, Response::HTTP_NOT_FOUND);
	}

	/**
	 * Return a 400 bad request error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorBadRequest($message = 'Bad Request')
	{
		// 400
		$this->error($message, Response::HTTP_BAD_REQUEST);
	}

	/**
	 * Return a 403 forbidden error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorForbidden($message = 'Forbidden')
	{
		// 403
		$this->error($message, Response::HTTP_FORBIDDEN);
	}

	/**
	 * Return a 500 internal server error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorInternal($message = 'Internal Error')
	{
		// 500
		$this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * Return a 401 unauthorized error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorUnauthorized($message = 'Unauthorized')
	{
		// 401
		$this->error($message, Response::HTTP_UNAUTHORIZED);
	}

	/**
	 * Return a 405 method not allowed error.
	 *
	 * @param string $message
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *
	 * @return void
	 */
	public function errorMethodNotAllowed($message = 'Method Not Allowed')
	{
		// 405
		$this->error($message, Response::HTTP_METHOD_NOT_ALLOWED);
	}

}