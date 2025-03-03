<?php

/*
    Custom Exception Handler
*/
class Exception_Class extends Exception {
    /*
        Magic __toString()
        @return string
    */

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public static function listClassVars() {
        $vars = get_class_vars(__CLASS__);
        $output = "";
        foreach ($vars as $var => $value) {
            $output .= "{$var} : {$value} <br/>";
        }
        return $output;
    }
}

?>