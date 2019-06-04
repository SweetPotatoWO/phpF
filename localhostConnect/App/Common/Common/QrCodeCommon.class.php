<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

use Common\Common\Phpqrcode\QRcode;

/**
 * Description of QrCodeCommon
 *
 * @author Administrator
 */
class QrCodeCommon {

    /**
     * @param type $content 二维码内容
     * @param type $size 二维码大小
     * @return type
     */
    public function createQrImg($content, $size = 10) {
        QRcode::png($content, false, "L", $size, 2);
    }

}
