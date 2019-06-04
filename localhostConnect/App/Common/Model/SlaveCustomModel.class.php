<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Model;

use Think\Model;

/**
 * Description of SlaveCustomModel
 *
 * @author DREAM
 */
class SlaveCustomModel {

    protected $SlaveDB = null;

    public function __construct() {
        $model = new Model();
        $this->SlaveDB = $model->db(1, "SlaveConfig");
    }

}
