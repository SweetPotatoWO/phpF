<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common\Phpqrcode;

class QRrs extends QRimage {

    public static $items = array();

    //----------------------------------------------------------------------
    public static function init_rs($symsize, $gfpoly, $fcr, $prim, $nroots, $pad) {
        foreach (self::$items as $rs) {
            if ($rs->pad != $pad)
                continue;
            if ($rs->nroots != $nroots)
                continue;
            if ($rs->mm != $symsize)
                continue;
            if ($rs->gfpoly != $gfpoly)
                continue;
            if ($rs->fcr != $fcr)
                continue;
            if ($rs->prim != $prim)
                continue;

            return $rs;
        }

        $rs = QRrsItem::init_rs_char($symsize, $gfpoly, $fcr, $prim, $nroots, $pad);
        array_unshift(self::$items, $rs);

        return $rs;
    }

}
