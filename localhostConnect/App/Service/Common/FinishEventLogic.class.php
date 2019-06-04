<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Common;

use Service\User\Logic\UserLogic;
use Service\Business\Logic\TenderLogic;
use Service\Business\Logic\HdAwardLogic;
use Service\Operate\Logic\CpsLogic;
use Common\Common\CostCompute;
use Service\Operate\Logic\TicketLogic;
use Service\Operate\Logic\IntegralLogic;
use Common\Common\Redis;
use Service\Business\Logic\BorrowLogic;
use Service\Operate\Logic\ActivityLogic;
use Service\Account\Logic\AccountRechargeLogic;
use Service\Operate\Model\ActivityModel;
use Service\User\Logic\UserInviteLogic;

/**
 * Description of FinishEvent
 *
 * @author DREAM
 */
class FinishEventLogic {

    /**
     * 注册事件
     * @param type $userID
     * @param type $phone
     * @return boolean
     */
    public function regEvent($userID) {
        
        return true;
    }
}
