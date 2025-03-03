<?php
class DeveloperSay {
	public function recommend() {
		return "Hi Turing";
	}
}

class AbstractService {
	protected $developerSay;

	public function __construct(DeveloperSay $java) {
		$this->developerSay = $java;
	}

	public function introduce() {
		return $this->developerSay->recommend();
	}
}

class SoftService {
	private $developerSay;

	public function __construct(DeveloperSay $java) {
		// parent::__construct($java);
		$this->developerSay = $java;
	}

	public function introduce() {
		return $this->developerSay->recommend();
	}
}

$developerSayObj = new DeveloperSay();
$abstractServiceObj = new AbstractService($developerSayObj);
echo $abstractServiceObj->introduce();
$softServiceObj = new SoftService($developerSayObj);
echo "<br/><br/>".$softServiceObj->introduce();
?>