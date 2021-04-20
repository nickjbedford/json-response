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
			$response = json_success([
				'hello' => 'world'
			]);
			
			$this->assertTrue($response->success);
			$this->assertNull($response->error);
			$this->assertCount(1, $response->data);
			$this->assertEquals('world', $response->data['hello']);
		}
		
		function testSuccessWithPayloadIsEncodedProperly()
		{
			$response = json_success([
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
			
			$response = json_exception($exception);
			
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
			
			$response = json_exception($exception);
			
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
		
		function testJsonResponseToArrayWorks()
		{
			$response = json_records([
				new SomeClass(), new SomeClass()
			]);
			
			$actual = $response->toArray();
			
			$this->assertEqualsRecursive([
				'success' => true,
				'reason' => null,
				'data' => [
					'records' => [
						[ 'a' => 123 ],
						[ 'a' => 123 ],
					],
					'page' => 0,
					'pageLength' => 2,
					'total' => 2,
					'next' => null,
					'previous' => null
				]
			], $actual);
		}
		
		function assertEqualsRecursive(array $expected, array $actual)
		{
			foreach($expected as $key=>$expectedValue)
			{
				$actualValue = $actual[$key];
				
				if (is_array($expectedValue))
					$this->assertEqualsRecursive($expectedValue, $actualValue);
				
				else if (is_object($expectedValue))
					$this->assertObjectEquals($expectedValue, $actualValue);
				
				else
					$this->assertEquals($expectedValue, $actualValue);
			}
		}
	}