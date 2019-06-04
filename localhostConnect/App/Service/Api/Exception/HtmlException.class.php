<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Exception;

/**
 * Description of HtmlException
 *
 * @author Administrator
 */
class HtmlException extends BaseException {

    public function __construct($code = 0, $message = '', $url = '') {
        parent::__construct(
                $message, 60000 + $code, $url
        );
    }

}
