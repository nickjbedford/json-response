<?php
	namespace JsonResponse;
	
	use PHPUnit\Framework\TestCase;
	use YetAnother\JsonRecordSet;
	
	class JsonRecordSetTests extends TestCase
	{
		function testSimpleRecordSetHasOnlyOnePage()
		{
			$response = json_records(range(10, 30, 10), 0, null, null, 'int');
			
			/** @var JsonRecordSet $data */
			$data = $response->data;
			
			$this->assertTrue($response->success);
			$this->assertEquals(3, $data->pageLength);
			$this->assertEquals('int', $data->recordType);
			$this->assertEquals(3, $data->total);
			$this->assertNull($data->nextPage);
			$this->assertNull($data->previousPage);
			$this->assertEquals(10, $data->records[0]);
			$this->assertEquals(20, $data->records[1]);
			$this->assertEquals(30, $data->records[2]);
		}
		
		function testRecordSetInMiddleOfQueryHasNextAndPreviousPages()
		{
			$response = json_records(range(10, 100, 10), 2, 10, 50);
			
			/** @var JsonRecordSet $data */
			$data = $response->data;
			
			$this->assertTrue($response->success);
			$this->assertEquals(10, $data->pageLength);
			$this->assertEquals(50, $data->total);
			$this->assertEquals(3, $data->nextPage);
			$this->assertEquals(1, $data->previousPage);
		}
		
		function testRecordSetAtStartOfQueryHasNextPage()
		{
			$response = json_records(range(10, 100, 10), 0, 10, 50);
			
			/** @var JsonRecordSet $data */
			$data = $response->data;
			
			$this->assertTrue($response->success);
			$this->assertEquals(10, $data->pageLength);
			$this->assertEquals(50, $data->total);
			$this->assertEquals(1, $data->nextPage);
			$this->assertNull($data->previousPage);
		}
	}