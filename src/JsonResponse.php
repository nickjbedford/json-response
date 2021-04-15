<?php
	namespace YetAnother;
	
	use Throwable;
	
	/**
	 * Represents a standardised JSON response that provides information about
	 * the request's success, error messages and codes as well as the data payload.
	 * @package YetAnother
	 */
	class JsonResponse
	{
		/** @var bool Specifies whether debug mode is enabled for exception responses. */
		public static bool $debug = false;
		
		/** @var bool Specifies whether the request succeeded or failed. */
		public bool $success = true;
		
		/** @var array $data Specifies the data payload. This is expected to be a valid object or array. */
		public array $data = [];
		
		/** @var JsonResponseError|null Specifies the information about an error. */
		public ?JsonResponseError $error = null;
		
		/**
		 * Specifies whether debug mode is enabled for exception responses.
		 * @param bool $debug
		 */
		public static function setDebug(bool $debug = true)
		{
			self::$debug = $debug;
		}
		
		/**
		 * Initialises a new JSON response.
		 * @param bool $success Whether or not the response succeeded.
		 * @param array $data The data payload.
		 * @param JsonResponseError|null $error Information about an error if the request failed.
		 */
		public function __construct(bool $success = true, array $data = [], ?JsonResponseError $error = null)
		{
			$this->success = $success;
			$this->data = $data;
			$this->error = $error;
		}
		
		/**
		 * Creates a JSON response indicating success with an optional data payload.
		 * @param array $data The data payload if applicable.
		 * @return static
		 */
		public static function success(array $data = []): self
		{
			return new self(true, $data);
		}
		
		/**
		 * Creates a JSON response indicating failure with an optional error code and data payload.
		 * @param string $errorMessage The error message to display to the user.
		 * @param string|null $errorCode An optional error code for the client to use in handling the error.
		 * @param array $errorData An optional data payload with further information
		 * @return static
		 */
		public static function failure(string $errorMessage, ?string $errorCode = null, array $errorData = []): self
		{
			return new self(false, [], new JsonResponseError($errorMessage, $errorCode, $errorData));
		}
		
		/**
		 * Creates a JSON response indication failure from an exception, with optional debugging data
		 * about the exception included in the error data payload.
		 * @param Throwable $exception The exception that was thrown.
		 * @param bool|null $includeDebugData Whether or not to include the debugging information about the
		 * exception in the error payload. This will default to the JsonResponse::$debugMode value.
		 * @return static
		 */
		public static function exception(Throwable $exception, ?bool $includeDebugData = null): self
		{
			return new self(false, [], JsonResponseError::fromException($exception, $includeDebugData ?? self::$debug));
		}
		
		/**
		 * @param bool|null $forceObjects
		 * @return string
		 */
		public function toJSON(?bool $forceObjects = null): string
		{
			return json_encode($this, $forceObjects ? JSON_FORCE_OBJECT : 0);
		}
		
		/**
		 * Converts the response to a JSON encoded string.
		 * @return string
		 */
		public function __toString()
		{
			return $this->toJSON();
		}
	}