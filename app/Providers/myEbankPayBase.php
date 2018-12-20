<?php
/*网银支付*/
namespace App\Providers;

class myEbankPayBase
{
    public $priKey;
    public $pubKey;
    public $params;
    public $mode;
    public $post;

    function getSignVeryfy($para_temp, $sign, $key) {
        return 'debug';
        //file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . print_r($para_temp, true) . "\r\n", FILE_APPEND);

        //除去待签名参数数组中的空值和签名参数
        //$para_filter = $this->paraFilter($para_temp);
        

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_temp);
        
        
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
    
        return 'debug';

        $isSgin = $this->md5Verify($prestr, $sign, $key);
    
        return $isSgin;
    }
    
    function paraFilter($para) {
        $para_filter = array();

        //file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . print_r($para_filter, true) . "\r\n", FILE_APPEND);

        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }


    
    function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //file_put_contents("log.txt","转义前:".$arg."\n", FILE_APPEND);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        //file_put_contents("log.txt","转义后:".$arg."\n", FILE_APPEND);
        return $arg;
    }
    
    function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr ."&paySecret=". $key;
        //file_put_contents("log.txt","prestr:".$logstr."\n", FILE_APPEND);
        $mysgin = strtoupper(md5($prestr));
    
        if($mysgin == $sign) {
            return true;
        }else{
            return false;
        }
    }

    function formatRequestParams($params,$app_secret) {
        ksort($params);
        $stringToBeSigned = "";
        foreach ($params as $k => $v)
        {
            if(is_string($v) && strlen($v) > 0)
            {
                $stringToBeSigned .= "$k=$v&";
            }
        }
        unset($k, $v);
        $stringToBeSigned = substr($stringToBeSigned, 0, -1);
        $stringToBeSigned .= "&paySecret=".$app_secret;
        $sign = strtoupper(md5($stringToBeSigned));
        return $sign;
    }

}