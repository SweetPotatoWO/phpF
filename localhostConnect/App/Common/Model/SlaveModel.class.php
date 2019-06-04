<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Model;

use Think\Model;

/**
 * 查询从库基类
 */
class SlaveModel extends Model {

    private $Slave = null;

    public function __construct() {
        parent::__construct();
    }

    protected function SlaveDB() {
        if ($this->Slave == null) {
            $this->Slave = clone $this;
            $this->Slave->db(1, "SlaveConfig");
        }
        return $this->Slave;
    }

}
