<?php
interface DeveloperSay {
	function recommend();
}

class JavaDeveloperSay implements DeveloperSay {
	function recommend() {
		return "I am a Java developer";
	}
}

class PhpDeveloperSay implements DeveloperSay {
	function recommend() {
		return "I am a PHP developer";
	}
}

class DeveloperService {
	protected $developerSay;
	public function __construct(DeveloperSay $java) {
		$this->developerSay = $java;
	}

	public function introduce() {
		return $this->developerSay->recommend();
	}
}

$javaObj = new JavaDeveloperSay();
$phpObj = new PhpDeveloperSay();

echo $javaObj->recommend();

// $developerSay = new DeveloperSay();
$developerServObj = new DeveloperService($javaObj);

echo "<br/><br/>".$developerServObj->introduce();

?>