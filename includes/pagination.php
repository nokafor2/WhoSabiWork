<?php

// This is a helper class to make paginating records easy. So there will be no need to make reference to the database class, other classes and the initialize file.
// 
class Pagination {
	
	public $current_page;
	public $per_page;
	public $total_count;

	public function __construct($page=1, $per_page=6, $total_count=0){
		$this->current_page = (int)$page;
		$this->per_page = (int)$per_page;
		$this->total_count = (int)$total_count;
	}

	public function offset() {
		// Assuming 20 items per page:
		// page 1 has an offset of 0    (1-1) * 20
		// page 2 has an offset of 20   (2-1) * 20
		//   in other words, page 2 starts with item 21
		return (($this->current_page - 1) * $this->per_page);
	}

	// Count the total number of pagination that will be needed
	public function total_pages() {
		//We take the ceiling because we always want to round up
		return ceil($this->total_count/$this->per_page);
	}
	
	// This gives the previous page 
	public function previous_page() {
		return $this->current_page - 1;
	}
  
	// This gives the next page
	public function next_page() {
		return $this->current_page + 1;
	}

	// Checks if there is a previous page
	public function has_previous_page() {
		return $this->previous_page() >= 1 ? true : false;
	}

	// Checks if there is a next page
	public function has_next_page() {
		return $this->next_page() <= $this->total_pages() ? true : false;
	}

	public function current_page() {
		return $this->current_page;
	}
}

?>