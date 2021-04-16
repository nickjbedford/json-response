<?php
	namespace YetAnother;
	
	/**
	 * Represents a paged set of records.
	 * @package YetAnother
	 */
	class JsonRecordSet
	{
		/** @var array Specifies the records in the current page. */
		public array $records;
		
		/** @var int Specifies the page index. */
		public int $page;
		
		/** @var int Specifies the total number of records in the query. */
		public int $total;
		
		/** @var int Specifies the length of each page in the query. */
		public int $pageLength;
		
		/** @var int|null Specifies the next page index if there are more records. */
		public ?int $nextPage;
		
		/** @var int|null Specifies the previous page index if the page index is non-zero. */
		public ?int $previousPage;
		
		/** @var string|null Optionally specifies the type of records in the set. */
		public ?string $recordType;
		
		/**
		 * Initialises a JSON record set.
		 * @param array $records The records in the current page.
		 * @param int $page The page index.
		 * @param int|null $pageLength The length of each page in the query,
		 * otherwise the number of records in the current page.
		 * @param int|null $totalRecords The total number of records in the query,
		 * otherwise the number of records in the current page.
		 * @param string|null $recordType Optionally specifies the type of records in the set.
		 */
		public function __construct(
			array $records = [],
			int $page = 0,
			?int $pageLength = null,
			?int $totalRecords = null,
			?string $recordType = null)
		{
			$this->records = $records;
			$this->page = max(0, $page);
			$this->total = $totalRecords ?? count($records);
			$this->pageLength = $pageLength ?? count($records);
			$this->previousPage = $page > 0 ? ($page - 1) : null;
			$this->nextPage = $this->total > (($this->page + 1) * $this->pageLength) ? ($this->page + 1) : null;
			$this->recordType = $recordType;
		}
	}