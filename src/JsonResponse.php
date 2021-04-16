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
		public bool $success;
		
		/** @var mixed|null $data Specifies the data payload. */
		public $data;
		
		/** @var JsonResponseError|null Specifies the information about an error. */
		public ?JsonResponseError $error;
		
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
		 * @param mixed|null $data The data payload.
		 * @param JsonResponseError|null $error Information about an error if the request failed.
		 */
		public function __construct(bool $success = true, $data = null, ?JsonResponseError $error = null)
		{
			$this->success = $success;
			$this->data = $data;
			$this->error = $error;
		}
		
		/**
		 * Creates a JSON response indicating success with an optional data payload.
		 * @param mixed|null $data The data payload if applicable.
		 * @return static
		 */
		public static function success($data = null): self
		{
			return new self(true, $data);
		}
		
		/**
		 * Creates a JSON response indicating success for a paged set of records.
		 * @param array $records The records in the current page.
		 * @param int $page The page index.
		 * @param int|null $pageLength The length of each page in the query,
		 * otherwise the number of records in the current page.
		 * @param int|null $totalRecords The total number of records in the query,
		 * otherwise the number of records in the current page.
		 * @param string|null $recordType Optionally specifies the type of records in the set.
		 * @return static
		 */
		public static function recordSet(
			array $records,
			int $page = 0,
			?int $pageLength = null,
			?int $totalRecords = null,
			?string $recordType = null): self
		{
			return self::success(new JsonRecordSet($records, $page, $pageLength, $totalRecords, $recordType));
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
			return new self(false, null, new JsonResponseError($errorMessage, $errorCode, $errorData));
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
			return new self(false, null, JsonResponseError::fromException($exception, $includeDebugData ?? self::$debug));
		}
		
		/**
		 * Converts the response to a JSON encoded string.
		 * @param bool|null $forceObject
		 * @return string
		 */
		public function toJSON(?bool $forceObject = null): string
		{
			return json_encode($this, $forceObject ? JSON_FORCE_OBJECT : 0);
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