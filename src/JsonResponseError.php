<?php
	namespace YetAnother;
	
	use Throwable;
	
	/**
	 * Represents the information about a failed request.
	 * @package YetAnother
	 */
	class JsonResponseError
	{
		/** @var string|null Specifies an optional error message for failures. */
		public ?string $message = null;
		
		/** @var string|null Specifies an optional error code for the client to use in handling the error. */
		public ?string $code = null;
		
		/** @var array Specifies any additional information about the error. */
		public array $data = [];
		
		/**
		 * Initialises a new response error.
		 * @param string|null $message The error message to display to the user.
		 * @param string|null $code The error code used to determine error handling.
		 * @param array $data Any additional information about the error.
		 */
		public function __construct(?string $message = null, ?string $code = null, array $data = [])
		{
			$this->message = $message;
			$this->code = $code;
			$this->data = $data;
		}
		
		/**
		 * Creates a JSON response error from an exception, with optional debugging data
		 * about the exception included in the error data payload.
		 * @param Throwable $exception
		 * @param bool $includeDebugData
		 * @return static
		 */
		public static function fromException(Throwable $exception, bool $includeDebugData = false): self
		{
			$data = $includeDebugData ? [
				'class' => get_class($exception),
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
				'trace' => $exception->getTraceAsString(),
			] : [];
			return new self($exception->getMessage(), $exception->getCode(), $data);
		}
	}