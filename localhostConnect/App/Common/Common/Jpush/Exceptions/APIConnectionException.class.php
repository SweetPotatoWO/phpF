<?php
namespace  Common\Common\Jpush\Exceptions;

class APIConnectionException extends JPushException {

    function __toString() {
        return "\n" . __CLASS__ . " -- {$message} \n";
    }
}
