<?php 
trait PTTrait {
	public function welcome() {
		return 'Welcome to Turing.';
	}
}

class PTTest {
	use PTTrait;

	public function welcome() {
		return 'I am a developer';
	}
}

$test = new PTTest();
echo $test->welcome();
?>