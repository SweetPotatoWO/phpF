<?php
namespace Common\Common\ThirdApi;

header("Content-type:text/html; charset=UTF-8");
/* *
 * 类名：ChuanglanCorpApi
 * 功能：创蓝工商接口请求类
 * 详细：构造创蓝工商接口请求，获取远程HTTP数据
 */

class ChuanglanCorpApi {
    const  API_BASE = 'https://api.253.com/open/'; //工商接口地址前缀

    const API_SEARCH = API_BASE.'company/search'; //模糊搜索

    const API_LICENSE = API_BASE.'company/business-license'; //营业执照
    const API_ENTIRE = API_BASE.'company/business-entire'; //全息接口

    const API_BALANCE = API_BASE.'get-balance'; //余额查询

    private $params = [
        'appId' => 'v4NxpGiw', // appId,登录万数平台查看
        'appKey' => 'lQUbdiQ1', // appKey,登录万数平台查看
    ];

    /**
     * 模糊查询
     * @param $keyword 关键词
     * @param int $pageSize 页尺寸
     * @param int $pageIndex 页码
     * @param bool $exactlyMatch 是否精确匹配
     * @return mixed
     */
	public function queryByKeyword($keyword,$pageSize=20,$pageIndex=0,$exactlyMatch=true) {
		$postArr = array_merge($this->params,[
            'keyWord' => $keyword, // 搜索关键字
            'exactlyMatch' => $exactlyMatch, // 是否精准匹配，默认否（传true代表精准匹配）
            'pageSize' => $pageSize, //每页条数，默认为10,最大不超过20条
            'pageIndex' => $pageIndex, //页码，默认第一页（从0开始），非必传
            'sourceKey' => true, //是否返回命中字段内容，默认否（传true返回命中字段内容）
            'searchType' => 1, //搜索范围
        ]);
		$result = $this->curlPost( self::API_SEARCH, $postArr);
		return $result;
	}
	
	/**
	 * 查询额度
	 *
	 *  查询地址
	 */
	public function queryBalance() {
		$result = $this->curlPost(self::API_BALANCE, $this->params);
		return $result;
	}
	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数 
	 * @return mixed
	 *  
	 */
	private function curlPost($url,$postFields){
		$postFields = json_encode($postFields);
		
		$ch = curl_init ();
		curl_setopt( $ch, CURLOPT_URL, $url ); 
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
			)
		);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,60); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
		curl_close ( $ch );
		return $result;
	}
	
}

