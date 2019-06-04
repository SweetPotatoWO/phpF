<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

/**
 *   公共 算法 封装类
 *
 * @author Administrator
 */
class Algorithm {

    public $initmode = '';

    /**
     * 排序树  
     * @staticvar array $tree
     * @param type $result 数据集合
     * @param type $html  子类样式 如 --  传递数字0 代表
     * @param type $pid 父类ID
     * @return type
     */
    public function DicTree($result, $html = "", $pid = 0, $treeHtml = '') {
        if (empty($this->initmode)) {
            $this->initmode = $html;
            $html = ""; //去掉顶级父类的标示
        }
        $treeArr = array();
        if ($pid > 0) {
            $initHtml = $this->initmode;
        }

        if (is_int($this->initmode)) {
            $initHtml+= $html;
        } else {
            $initHtml .= $html;
        }
        foreach ($result as $key => $v) {
            if ($v['parentID'] == $pid) {
                if (empty($treeHtml)) {
                    $vhtml = $v['ID'];
                }else{
                    $vhtml = $treeHtml . "," . $v['ID'];
                }
                $v["sub"] = $vhtml;
                $v['html'] = $initHtml;
                array_push($treeArr, $v);
                $arr = $this->DicTree($result, $initHtml, $v['ID'], $vhtml);
                if (!empty($arr)) {
                    $treeArr = array_merge($treeArr, $arr);
                }
            }
        }
        return $treeArr;
    }
    
   /**
    * 格式化成需要的数据
    * @param type $result
    * @param type $html
    * @param type $pid
    * @param type $treeHtml
    * @return type
    */
    public function dicTreeFormat($result, $html = "", $pid = 0, $treeHtml = ''){
        $list = $this->DicTree($result, $html, $pid,$treeHtml);
        $arr = array();
        foreach ( $list as  $v ){
            $pos = strrpos($v["sub"],",");
            $v["parent"] = $v["sub"];
            if(!empty($pos)){
               $v["parent"] = substr($v["sub"],0, $pos) ;
            } 
            $arr[] = $v;
        }
        return $arr;
    }

}
