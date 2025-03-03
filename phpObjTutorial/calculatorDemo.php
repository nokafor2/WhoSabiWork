<?php

require_once('../includes/exception_class.php');

interface calcFunctions {
    function add($val1, $val2);
    function subtract($val1, $val2);
    function multiply($val1, $val2);
    function divide($val1, $val2);
}

abstract class AyuaMathFunctions implements calcFunctions {
    const DIVISION_BY_ZERO = 1000;

    abstract public function add($val1, $val2);

    public function subtract($val1, $val2) {
        return $val1 - $val2;
    }

    final public function multiply($val1, $val2) {
        return $val1 * $val2;
    }

    public function divide($val1, $val2) {
        /* try {
            if ($val2 != 0) {
                $output = $val1 / $val2;        
                return $output;
            } else {
                throw new Exception_Class('Trying to divide by zero', self::DIVISION_BY_ZERO);
            }
        } catch (Exception_Class $e) {
            // echo "<pre>";
                // print_r($e);
            // echo "</pre>";
            return $e;
        } */

        if ($val2 != 0) {
            $output = $val1 / $val2;        
            return $output;
        } else {
            throw new Exception_Class('Trying to divide by zero', self::DIVISION_BY_ZERO);
        }
    }    
}



class Calculator extends AyuaMathFunctions {
    public static $val1 = 5;
    public static $val2 = 5; 

    public $input1 = 5;
    public $input2 = 5; 

    /* function __construct($val1, $val2) {
        $this->val1 = $val1;
        $this->val2 = $val2;
    } */

    function display1() {
        try {
            $output = "The division of ".self::$val1." and ".self::$val2." = ".$this->divide(self::$val1, self::$val2);
        } catch(Exception_Class $e) {
            return $e;
        }
        
        return $output;
    }

    function display2() {
        try {
            $result = $this->divide($this->input1, $this->input2);
            $output = "The division of ".$this->input1." and ".$this->input2." = ".$result;
        } catch(Exception_Class $e) {
            return $e;
        }
        
        return $output;
    }

    public function add($val1, $val2) {
        return $val1 + $val2;
    }

    // Lesson: A normal class cannot contain an abstract method.
    // abstract public function myAdd($val1, $val2);
}

class Chamber extends Calculator {
    public $pressure;
    public $temperature;
    /* public $val1;
    public $val2; */

    function __construct() {
        /* $this->val1 = 20;
        $this->val2 = 15; */
    }

    public function infoClass() {
        return "<br/>This function is the Chamber class.<br/>";
    }

    public function displayVals1() {
        return "<br/>Val1 is: ".self::$val1." and Val2 is: ".self::$val2;
    }

    public function displayVals2() {        
        return "<br/>Val1 is: ".$this->input1." and Val2 is: ".$this->input2;
    }

    public function displayVals3() {
        $output = "<br/>";
        $vars = get_class_vars('Chamber');
        foreach ($vars as $var => $value) {
            $output .= "{$var} : {$value} <br/>";
        }
        return $output;
    }

    public function declareVals1() {
        self::$val1 = 32;
        self::$val2 = 2;
    }

    public function declareVals2() {
        $this->input1 = 20;
        $this->input2 = 0;
    }

    public function myAdd() {
        return "Result from myAdd.";
    }

    // create a function to dynamically create a new instance
    final public static function getInstance($instanceId) {
        $class_name = __CLASS__ . "_".$instanceId;
        if (!class_exists($class_name)) {
            return "Instance of class doesn't exist";
        }
        return new $class_name();
    }
}

$methods = get_class_methods('AyuaMathFunctions');
echo "<br/>Methods for AyuaMathFunctions class are: <br/>";
foreach($methods as $method) {
    echo "Method: ".$method."<br/>";
}

$vars = get_class_vars('AyuaMathFunctions');
echo "<br/>Variables for AyuaMathFunctions class are: <br/>";
foreach ($vars as $var => $value) {
    echo "{$var} : {$value} <br/>";
}

echo " ****************************** ";

/* ****************************** */

$methods = get_class_methods('Exception_Class');
echo "<br/>Methods for Exception_Class class are: <br/>";
foreach($methods as $method) {
    echo "Method: ".$method."<br/>";
}

echo "<br/>Variables for Exception_Class class are: <br/>";
/* $vars = get_class_vars('Exception_Class');
foreach ($vars as $var => $value) {
    echo "{$var} : {$value} <br/>";
} */
// $exceptionClass = new Exception_Class();
echo Exception_Class::listClassVars();

echo " ****************************** ";

/* ****************************** */

$methods = get_class_methods('Chamber');
echo "<br/>Methods for Chamber class are: <br/>";
foreach($methods as $method) {
    echo "Method: ".$method."<br/>";
}

echo "<br/>Variables for Chamber class are: <br/>";
$vars = get_class_vars('Chamber');
foreach ($vars as $var => $value) {
    echo "{$var} : {$value} <br/>";
}

echo " ****************************** ";

/* ****************************** */

/* echo "<br/>";
$calculator = new Calculator(5,5);
echo $calculator->display(); */

echo "<br/><br/>";
$chamber = new Chamber();
echo "<br/>Default variables has been changed here:<br/>";
$chamber->declareVals2(); 
echo "<br/>Display variables with function from parent class:<br/>";
echo $chamber->display2();
echo "<br/>Display variables with function from child class:<br/>";
echo $chamber->displayVals2();
echo "<br/>Display all variables with function from child class:<br/>";
echo $chamber->displayVals3();
echo "<br/>Check again for variables in child class:<br/>";
echo $chamber->displayVals2();

/* Testing static variables */
echo "<br/><br/><br/> ****************************** ";
echo "<br/>Testing with static variables <br/>";
echo "<br/>Default variables has been changed here:<br/>";
$chamber->declareVals1(); 
echo "<br/>Display variables with function from parent class:<br/>";
echo $chamber->display1();
echo "<br/>Display variables with function from child class:<br/>";
echo $chamber->displayVals1();
echo "<br/>Display all variables with function from child class:<br/>";
echo $chamber->displayVals3();

echo "</br> *************************** </br>";

$test_object = (object) array(
    'yesterday' => 'Wednesday',
    'today' => 'Thursday',
    'tomorrow' => 'Friday'
);

echo "<pre>";
    print_r($test_object);
echo "</pre>";

echo "Today is: ".$test_object->today;

Chamber::getInstance(1);

if (class_exists('Chamber_1')) {
    echo "</br> New chamber class exists: ";    
}

echo "<br/><br/>".$chamber->myAdd();

?>