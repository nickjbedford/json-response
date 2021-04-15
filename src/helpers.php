<?php
	use YetAnother\JsonResponse;
	
	if (!function_exists('json_success'))
	{
		/**
		 * Creates a JSON response indicating success with an optional data payload.
		 * @param array $data The data payload if applicable.
		 * @return JsonResponse
		 */
		function json_success(array $data = []): JsonResponse
		{
			return JsonResponse::success($data);
		}
	}
	
	if (!function_exists('json_failure'))
	{
		/**
		 * Creates a JSON response indicating failure with an optional error code and data payload.
		 * @param string $errorMessage The error message to display to the user.
		 * @param string|null $errorCode An optional error code for the client to use in handling the error.
		 * @param array $errorData An optional data payload with further information
		 * @return JsonResponse
		 */
		function json_failure(string $errorMessage, ?string $errorCode = null, array $errorData = []): JsonResponse
		{
			return JsonResponse::failure($errorMessage, $errorCode, $errorData);
		}
	}
	
	if (!function_exists('json_exception'))
	{
		/**
		 * Creates a JSON response indication failure from an exception, with optional debugging data
		 * about the exception included in the error data payload.
		 * @param Throwable $exception The exception that was thrown.
		 * @param bool|null $includeDebugData Whether or not to include the debugging information about the
		 * exception in the error payload. This will default to the JsonResponse::$debugMode value.
		 * @return JsonResponse
		 */
		function json_exception(Throwable $exception, ?bool $includeDebugData = null): JsonResponse
		{
			return JsonResponse::exception($exception, $includeDebugData);
		}
	}