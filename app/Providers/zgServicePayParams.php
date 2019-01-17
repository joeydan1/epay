<?php
namespace App\Providers;
use App\Providers\DBHandller;

class zgServicePayParams {

    public $result = TRUE;
    public $message = "";

    public function __construct($mode, $post){
        $this->loadParams($mode, $post);
    }

    public function loadParams($mode, $post){

        //$extraObj = json_decode(stripslashes($post['extra']));
        //$signObj = json_decode(stripslashes($post['sign']));

        switch($mode){
            case 'servicePay';
                $this->params = servicePayLoad($post);
                break;
            default:
                return array('result' => FALSE, 'message'=> 'no matched mode found');
        }
    }


    public function servicePayLoad($post){
        $extraObj = json_decode(stripslashes($post['extra']));

        //get infomation from DB with post MID
        $db =  new DBHandller(); 
        $return = $db->zgMidSearch($extraObj->{'memberId'});
        if (! $return['result']){
            $this->result = FALSE;
            $this->message = "could not find merchant record form DB with post mid";
            return;
        } 
        $zgMmerchant = $return['data'];
        $params['partner_id'] = $zgMmerchant->mid; //zg mid
        $params['priKey'] = $zgMmerchant->priKey;
        $params['pubKey'] = $zgMmerchant->pubKey; //'./public.pem'
        $params['md5Str'] = $zgMmerchant->md5Str; //"ZD7D0ZM7SC8CVV6Q"

        $merdatetime = date('Y-m-d H:i:s', time());
        $servicename = 'fund_agent_pay';
        $out_trade_no = $post['mchOrderNo']; //order No.
        $account = $params['partner_id']; //my MID
        $account_type = 'C';

        $detail_order_no = $out_trade_no.'0001';
        $link_num = '';  //123456789
        $province = '';
        $city = '';
        $bank_name = '';
        $subbranch_name = '';
        $real_name = '';
        $card_no = ''; //6217690700196181

        $amount = '';//1000.00
        $pay_reason = ''; //物流支付代付zhuanzhang01
        $comment = ''; //货款支付,合同号码：HT1601180005

        $show_url = ''; //http://222.73.146.2ewexchange/b-0-s/contract.do
        $subject = ''; //货款支付,合同号码：HT1601180006
        $server_return_url = 'http://172.16.5.nge/zgytCallbackService.do';
        $page_return_url = '';//http://222.73.wexchange/b-0-s/contract.do

        $data='<?xml version="1.0" encoding="utf-8"?>
               <pay_interaction>
               <request>
                    <head>
                        <merdatetime>'. $merdatetime .'</merdatetime>
                        <servicename>'. $servicename .'</servicename> <ver>1.0</ver>
                    </head>
                    <param>
                        <out_trade_no>'. $out_trade_no .'</out_trade_no>
                        <account>'. $account .'</account>
                        <account_type>'.$account_type.'</account_type>
                        <order_list>
                            <order> 
                                <detail_order_no>'. $detail_order_no .'</detail_order_no>
                                <link_num>'. $link_num .'</link_num>
                                <province>'.$province.'</province>  
                                <city>'.$city.'</city>  
                                <bank_name>'.$bank_name.'</bank_name>  
                                <subbranch_name>'.$subbranch_name.'</subbranch_name>  
                                <real_name>'.$real_name.'</real_name>  
                                <card_no>'.$card_no.'</card_no>  
                                <amount>'.$amount.'</amount>  
                                <pay_reason>'.$pay_reason.'</pay_reason>  
                                <comment>'.$comment.'</comment> 
                            </order>  
                        </order_list>
                        <show_url>'.$show_url.'</show_url>  
                        <subject>'.$subject.'</subject>  
                        <server_return_url>'.$server_return_url.'</server_return_url>  
                        <page_return_url>'.$page_return_url.'</page_return_url> 
                    </param>
                </request>
                </pay_interaction>';


        //protocol configurations
        $params['partner_id'] = '';
        $params['service_name'] = 'fund_agent_pay_query';
        $params['rsamsg'] = '';
        $params['md5msg'] = '';
        $params['version'] = '3.0';

        $params['remote_url'] = 'https://pay.g-pay.cn/servicePay.html';
        $params['urlencode_data'] = urlencode(preg_replace('/\s+/', '', $data));
        $params['return_url'] = '';
        
        //encrytion keys
        /*$db =  new DBHandller(); 
        $return = $db->zgMidSearch($this->params['head']['mid']);
        if (! $return['result']){
            $this->result = FALSE;
            $this->message = "cannnot get merchange record form DB with mid";
            return;
        } 
           
        $zgMmerchant = $return['data'];
        $params['partner_id'] = $zgMmerchant->mid;
        $params['priKey'] = $zgMmerchant->priKey;
        */            
        $params['rsamsg'] = "partner_id=".$params['partner_id'].
                            "&service_name=".$params['service_name'].
                            "&data= ".$params['urlencode_data'].
                            "&return_url=".$params['return_url'];

        $pkcs12certdata = file_get_contents($params['pubKey']);
        $public_key_id= openssl_get_publickey($pkcs12certdata);    
                    
        $split = str_split($rsamsg, 100);// 1024bit && OPENSSL_PKCS1_PADDING  不大于117即可

        $rsabin = '';
        foreach ($split as $part) {
            $isOkay = openssl_public_encrypt($part, $en_data, $public_key_id, OPENSSL_PKCS1_PADDING);
            if(!$isOkay){
                $this->result = FALSE;
                $this->message = "openssl_public_encrypt failed";
                return;
            }
            $rsabin .= $en_data;	
        }

        $params['rsamsg'] = base64_encode($rsabin);
        $params['md5msg'] =  md5($params['md5Str']);

        return $params;
    }


}














?>