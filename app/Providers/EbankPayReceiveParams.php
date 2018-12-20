<?php
namespace App\Providers;
date_default_timezone_set("Asia/Shanghai");

class EbankPayReceiveParams
{
    public static function loadParams($mode, $post)
    {
        switch($mode){
            case 'eBankQuery';
                return array(
                    'head' => array(
                        'version' => '1.0',
                        'method' => 'sandpay.trade.query',
                        'productId' => '00000007',
                        'accessType' => '1',
                        'mid' => $post['mid'],
                        'channelType' => '07',
                        'reqTime' => date('YmdHis', time()),
                    ),
                    'body' => array(
                        'orderCode' => $post['orderCode'], //订单号
                        'extend' => '',
                    )
                );
                break;

            case 'eBankPay';
                $extraObj = json_decode(stripslashes($post['extra']));
                return array(
                    'head' => array(
                        'version' => '1.0',
                        'method' => 'sandpay.trade.pay',
                        'productId' => '00000007',
                        'accessType' => '1',
                        'mid' => $extraObj->{'memberId'},
                        'channelType' => '07',
                        'reqTime' => date('YmdHis', time())
                    ),
                    'body' => array(
                        'orderCode' => $post['mchOrderNo'],
                        'totalAmount' => sprintf( '%012d', $post['amount']*100),  //sprintf('%012d', $value*100);
                        'subject' => $post['remark'],
                        'body' => $post['body'],
                        //'txnTimeOut' => $post['txnTimeOut'],
                        'payMode' => 'bank_pc',
                        'payExtra' => array('payType' => '1', 'bankCode' => $extraObj->{'bankType'}),
                        'clientIp' => '203.18.50.4',
                        'notifyUrl' => 'http://132.232.241.112/sandpayEbankpayNotify',
                        'frontUrl' => $extraObj->{'callbackUrl'},
                        'extend' => '',
                    )
                );
                break;

            case 'ebankPayNotify';
                return array(
                    'sign' => $post['sign'],
                    'signType' => $post['signType'],
                    'data' => $post['data'],
                    'charset' => $post['charset'],
                );
                break;
        }
    }
}

?>