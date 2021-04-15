<?php
	namespace JsonResponse;
	
	use InvalidArgumentException;
	use PHPUnit\Framework\TestCase;
	use YetAnother\JsonResponse;
	
	class JsonResponseTests extends TestCase
	{
		protected function setUp(): void
		{
			parent::setUp();
			JsonResponse::setDebug(false);
		}
		
		function testSuccessWithPayloadContainsDataAndHasNoError()
		{
			$response = JsonResponse::success([
				'hello' => 'world'
			]);
			
			$this->assertTrue($response->success);
			$this->assertNull($response->error);
			$this->assertCount(1, $response->data);
			$this->assertEquals('world', $response->data['hello']);
		}
		
		function testSuccessWithPayloadIsEncodedProperly()
		{
			$response = JsonResponse::success([
				'hello' => 'world',
				'array' => [
					10, 'Text'
				]
			]);
			
			$json = strval($response);
			$result = json_decode($json, true);
			
			$this->assertTrue($result['success']);
			$this->assertNull($result['error']);
			$this->assertCount(2, $response->data);
			$this->assertEquals('world', $response->data['hello']);
			$this->assertEquals(10, $response->data['array'][0]);
			$this->assertEquals('Text', $response->data['array'][1]);
		}
		
		function testExceptionCreatesValidErrorResponseWithoutDebugData()
		{
			$exception = new InvalidArgumentException('An argument provided was invalid.', 10);
			
			$response = JsonResponse::exception($exception);
			
			$this->assertFalse($response->success);
			$this->assertEmpty($response->data);
			$this->assertNotNull($response->error);
			$this->assertEquals($exception->getMessage(), $response->error->message);
			$this->assertEquals($exception->getCode(), $response->error->code);
			$this->assertEmpty($response->error->data);
		}
		
		function testExceptionInDebugModeCreatesValidErrorResponseWithDebugData()
		{
			$exception = new InvalidArgumentException('An argument provided was invalid.', 10);
			JsonResponse::setDebug();
			
			$response = JsonResponse::exception($exception);
			
			$this->assertFalse($response->success);
			$this->assertEmpty($response->data);
			$this->assertNotNull($response->error);
			$this->assertEquals($exception->getMessage(), $response->error->message);
			$this->assertEquals($exception->getCode(), $response->error->code);
			$this->assertEquals($exception->getLine(), $response->error->data['line']);
			$this->assertEquals($exception->getFile(), $response->error->data['file']);
			$this->assertEquals($exception->getTraceAsString(), $response->error->data['trace']);
			$this->assertEquals(get_class($exception), $response->error->data['class']);
		}
	}