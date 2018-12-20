<?php
/*网银支付*/
namespace App\Providers;

class myEbankPayParams
{
    public static function loadParams($mode, $post){

        $extraObj = json_decode(stripslashes($post['extra']));
        $signObj = json_decode(stripslashes($post['sign']));
        

        switch($mode){
            case 'tradingRecordInsert';
                return array(
                    'mid' => $extraObj->{'memberId'},
                    'orderCode' => $request['mchOrderNo'],
                    'totalAmount' => $request['amount'],
                    'subject' => $request['remark'],
                    'body' =>  $request['body'],
                    //'txnTimeOut' =>  $extraObj->{'orderPeriod'},
                    'payMode' => 'bank_pc',
                    'bankCode' => $extraObj->{'bankType'},
                    'payType' =>  '1',  //1. 1-网银支付（借记卡) 3-混合通道（借/贷记卡均可使用）
                    //'clientIp' => '127.0.0.1',
                    'clientIp' => $request->ip(),
                    'notifyUrl' => 'http://192.168.22.171/sandpay-qr-phpdemo.bak/test/dist/notifyurl.php',
                    'frontUrl' =>   'http://61.129.71.103:8003/jspsandpay/payReturn.jsp',
                    'mode' => 'eBankPay',
                    //'extend' => '',
                );
                break;
        }


    }



}