<?php

namespace App\Libraries;

class Output
{
    public $success;
    public $message;
    public $data;
    
    public function __construct() {
        $this->success = false;
        $this->message = null;
        $this->data = new \StdClass();
    }

    public function __set($name, $value) {
        throw new \Exception("Cannot add new property \$$name to instance of " . __CLASS__);
    }
    
    public function get()
    {
        $ret = [
            'success' => $this->success,
            'message' => $this->message
        ];

        if (!is_null($this->data)) {
            $ret = array_push($ret, ['data' => $this->data]);
        }

        return (object) $ret;
        
    }
}
