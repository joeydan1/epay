<?php
namespace App\Providers;

class GeneralPayReceiveParams
{

    public static function loadParams($mode, $post)
    {
        if (isset($mode)) {
            switch ($mode) {
                case 'agentpay';
                    return array(
                        'transCode' => 'RTPM', // 实时代付
                        'merId' => $post['mid'], // 此处更换商户号
                        'url' => '/agentpay',
                        'pt' => array(
                            'orderCode' => 'Y9999999999996',
                            'version' => '01',
                            'productId' => '00000004',
                            'tranTime' => date('YmdHis', time()),
                            //'timeOut' => '20181024120000',
                            'tranAmt' => $post['tranAmt'],
                            'currencyCode' => '156',
                            'accAttr' => '0',
                            'accNo' => $post['accNo'],
                            'accType' => '4',
                            'accName' => $post['accName'],
                            //'provNo' => 'sh',
                            //'cityNo' => 'sh',
                            'bankName' => $post['bankName'],
                            //'bankType' => '1',
                            'remark' => 'pay',
                            'payMode' => '2',
                            'channelType' => '07'
                        )
                    );
                    break;
                case 'collection';
                    return array(
                        'transCode' => 'RTCO', // 实时代收
                        'merId' => $post['mid'], // 此处更换商户号
                        'url' => '/collection',
                        'pt' => array(
                            'orderCode' => '500000100018',
                            'version' => '01',
                            'productId' => '00000002',
                            'tranTime' => date('YmdHis', time()),
                            'timeOut' => '20161024120000',
                            'tranAmt' => '000000000500',
                            'currencyCode' => '156',
                            'accAttr' => '0',
                            'accType' => '4',
                            'accNo' => '6226220209634996',
                            'accName' => '全渠道',
                            'bankName' => '全渠道',
                            'provNo' => '010000',
                            'cityNo' => '010000',
                            'certType' => '0001',
                            'certNo' => '321281198702253717',
                            'cardId' => '321281198702253717',
                            'phone' => '12345678901',
                            'bankInsCode' => '48270000',
                            'purpose' => 'collection',
                            'channelType' => '07'
                        )
                    );
                    break;
                case 'idCardVerify';
                    return array(
                        'transCode' => 'RNPA', // 实名公安认证
                        'merId' => '100211701160001', // 此处更换商户号
                        'url' => '/idCardVerify',
                        'pt' => array(
                            'orderCode' => '400000000011',
                            'version' => '01',
                            'productId' => '00000003',
                            'tranTime' => date('YmdHis', time()),
                            'name' => '罗福',
                            'certType' => '0001',
                            'certNo' => '350424198806210053',
                            'returnPic' => '1',
                        )
                    );
                    break;
                case 'queryBalance';
                    return array(
                        'transCode' => 'MBQU', // 商户余额查询
                        'merId' => $post['mid'], // 此处更换商户号
                        'url' => '/queryBalance',
                        'pt' => array(
                            'orderCode' => '200000001048',
                            'version' => '01',
                            'productId' => '00000003',
                            'tranTime' => date('YmdHis', time())
                        )
                    );
                    break;
                case 'queryAgentpayFee';
                    return array(
                        'transCode' => 'PTFQ', // 代付手续费查询
                        'merId' => $post['mid'], // 此处更换商户号
                        'url' => '/queryAgentpayFee',
                        'pt' => array(
                            'orderCode' => '100000011171',
                            'version' => '01',
                            'productId' => '00000003',
                            'tranTime' => date('YmdHis', time()),
                            'tranAmt' => '000000020000',
                            'currencyCode' => '156',
                            'accAttr' => '1',
                            'accType' => '4',
                            'accNo' => '5187180008861234'
                        )
                    );
                    break;
                case 'queryOrder';
                    return array(
                        'transCode' => 'ODQU', // 订单查询
                        'merId' => $post['mid'], // 此处更换商户号
                        'url' => '/queryOrder',
                        'pt' => array(
                            'orderCode' => $post['orderCode'],
                            'version' => '01',
                            'productId' => '00000003',
                            'tranTime' => $post['tranTime']
                        )
                    );
                    break;
                case 'realNameVerify';
                    return array(
                        'transCode' => 'RNAU', // 实名认证
                        'merId' => '100211701160001', // 此处更换商户号
                        'url' => '/realNameVerify',
                        'pt' => array(
                            'orderCode' => '3000010010131',
                            'version' => '01',
                            'productId' => '00000003',
                            'tranTime' => date('YmdHis', time()),
                            'accAttr' => '0',
                            'accType' => '4',
                            'accNo' => '6216261000000000018',
                            'accName' => '汪中',
                            'certType' => '0101',
                            'certNo' => '350821198706134513',
                            'phone' => '15970847045'
                        )
                    );
                    break;
                case 'getClearFileContent';
                    return array(
                        'transCode' => 'CFCT', // 对账单申请
                        'merId' => '100211701160001', // 此处更换商户号
                        'url' => '/getClearFileContent',
                        'pt' => array(
                            'version' => '01',
                            'clearDate' => '20170530',
                            'busiType' => '1',
                            'fileType' => '1'
                        )
                    );
                    break;
                default;
                    echo 'no demo:' . $mode;
                    exit;
            }
        } else {
            echo 'mode not set';
            return view('greeting', ['name'=>'mode not set']);
            exit;
        }
    }

}