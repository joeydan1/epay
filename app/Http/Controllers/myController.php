<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Providers\EbankPayReceive;
use App\Providers\GeneralPayReceive;
use App\Providers\DBHandller;
use App\Providers\myEbankPay;

date_default_timezone_set("Asia/Shanghai");
//require_once('//home//vagrant//code//Laravel/app//Providers//GeneralPayReceive.php');
//use Merchant_Demo_PHP\Merchant_Demo\common;
//require ('common.php');
//foreach ($myTradingRecords as $myTradingRecord){
//    file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . print_r($myTradingRecord, true) . "\r\n", FILE_APPEND);
//}

class myController extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
    }
    

    public function login(){
        return view('greeting', ['name'=>'James']);
    }


    public function sandpayEbankpayNotify(Request $request){
        
        $sandpayEbankpayNotifyObj = new EbankPayReceive('ebankPayNotify', $request);
        $result = $sandpayEbankpayNotifyObj->execute();

        return "notify debug";
        /*
        if ($result['result']){
            $db = new DBHandller();
            $result = $db->sandpayEbankPayNotifyInsert($result);
        }
        return $result['message'];*/
    }


    public function myMerchantRegister(Request $request){
        $db = new DBHandller();
        
        $result = $db->myMerchantRegister($request->all());
        if ($result['result']){
            $result = 'merchantRegister sucessfully.';
        }else{
            $result = $result['message'];
        }

        return view('resultReturn', ['result' => $result]);
    }


    public function myMerchantSearch(){
        $db = new DBHandller();
        $return = $db->myMerchantSearch();

        if($return['result']){
            return view('myMerchantSearch', ['myMerchants'=> $return['data']]);
        }
        else{
            return view('resultReturn', [ 'result' => $return['message'] ]);
        }
    } 


    public function myEbankPayTrading(){
        $myTradingRecords = DB::table('myEbankTrading')->get();
        return view('myEbankPayTrading', ['myTradingRecords'=>$myTradingRecords]);
    } 


    public function sandpayMerchantRegister(Request $request){
        $db = new DBHandller();
        $result = $db->sandpayMerchantRegister($request->all());
        if ($result['result']){
            $result = 'sandpayMerchantRegister sucessfully.';
        }else{
            $result = $result['message'];
        }
        return view('resultReturn', ['result' => $result]);
    }


    public function sandpayMerchantSearch(){
        $sandpayMerchant = DB::table('sandpayMerchant')->get();
        return view('sandpayMerchantSearch', ['sandpayMerchant'=>$sandpayMerchant]);
    }
    
    public function sandpayEbankPayTrading(){
        $records = DB::table('sandpayEbankTrading')->get();
        return view('sandpayEbankPayTrading', ['records'=>$records]);
    }


    public function eBankAPIHandel(Request $request){
        $myEbankPayObj = new myEbankPay('tradingRecordInsert', $request); 
        $return = $myEbankPayObj->execute();

        if ($return['result']){
            $EbankPayReceiveObj = new EbankPayReceive('eBankPay', $request);
            $return = $EbankPayReceiveObj->execute();
            
            if (!$return['result']) return $return['message']; 
            
            $sandpayReturnObj = json_decode($return['data']);
            $bolResult = property_exists($sandpayReturnObj, 'submitUrl');
            file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . print_r($sandpayReturnObj, true) . "\r\n", FILE_APPEND);
            
            if ($bolResult){
                $postData = http_build_query($sandpayReturnObj->{'params'});
                $finalBankLink = $sandpayReturnObj->{'submitUrl'}.'?'.$postData;

                //file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . print_r($finalBankLink, true) . "\r\n", FILE_APPEND);

                $request['finalBankLink'] = 
                    'txnTime:' . $sandpayReturnObj->{'params'}->{'txnTime'} . ',' . 
                    'certId:' . $sandpayReturnObj->{'params'}->{'certId'} . ',' .
                    'merId:' . $sandpayReturnObj->{'params'}->{'merId'};
                    
                $db = new DBHandller();
                $return = $db->sandpayEbankTradingInsert($request);

                if ($return['result']){
                    $extraObj = json_decode(stripslashes($request['extra']));

                    $db = new DBHandller();
                    $return = $db->myPayKey($extraObj->{'memberId'});
                    
                    if ($return['result']){
                        $myPayKey = $return['myPayKey'];
                        $stringToBeSigned = $finalBankLink."&paySecret=".$myPayKey;
                        $sign = strtoupper(md5($stringToBeSigned));
                        return array('result'=> True, 'finalBankLink'=> $finalBankLink, 'sign'=>$sign);
                       
                    }else return $return;

                }else return $return;
            
            }else return array('result' => False, 'message'=>'submit url is null');
            
        }else return $return;

    }

    public function epayTest($mode, Request $request)
    {
        /*EbankPay*/ 
        $eBankPayPost = array(
            'mid' => '100211701160001',
            'orderCode' => 'T'.date("YmdHis"),
            'totalAmount' => '000000000012',
            'subject' => '话费充值',
            'body' => '用户购买话费0.12',
            'txnTimeOut' => '20181230000000',
            'payMode' => 'bank_pc',
            'bankCode' => '03080000', //招商银行
            'payType' =>  '1',  //1. 1-网银支付（借记卡) 3-混合通道（借/贷记卡均可使用）
            'clientIp' => '127.0.0.1',
            'notifyUrl' => 'http://192.168.22.171/sandpay-qr-phpdemo.bak/test/dist/notifyurl.php',
            'frontUrl' =>   'http://61.129.71.103:8003/jspsandpay/payReturn.jsp',
            //'extend' => '',
        );

        /*agentPay*/
        $agentPayPost = array(
            'mid' => '100211701160001',
            'accNo' => '6216261000000000018',
            'tranAmt' => '000000000500',
            'accName' => '全渠道',
            'bankType' => '',
            'bankName' => 'cbc',
        );

        $agentQueryPost = array(
            'mid' => '100211701160001',
            'tranTime' => '20181230000000',
            'orderCode' => '500000100018',
        );

        //print_r($mode);
        $jobj = json_decode(stripslashes($request['data']));
        
        $arr = array();
        foreach ($jobj as $key =>$value){
            $arr[$key] = $value; 
        }
        
        file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . $arr['mid'] . "\r\n", FILE_APPEND);
        
        $result = $mode;
        
        return view('greeting', ['name'=>$result]);
    }
}