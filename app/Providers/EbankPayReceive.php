<?php
/*网银支付*/
namespace App\Providers;
use App\Providers\DBHandller;

class EbankPayReceive extends PayReceiveBase
{
    /*production*/
    const API_HOST = 'https://cashier.sandpay.com.cn/gateway/api';

    public function __construct($mode, $post)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->params = EbankPayReceiveParams::loadParams($mode, $post);
        
        /*
        $db =  new DBHandller(); 
        $return = $db->sandpayMerchantMidSearch($this->params['head']['mid']);
        if ($return['result'] == True){
            $this->params['head']['mid'] = $return['data'];
            return array('result'=>True, 'message'=> 'found mid');
        }else{
            return $return;
        }
        */
    }


    public function execute()
    {
        if (isset($this->mode)){
            switch($this->mode){
                case 'query';
                    return $this->query();
                    break;
                case 'eBankPay';
                    return $this->orderPay();
                    break;
                case 'ebankPayNotify';
                    return $this->notify();
                    break;
            }
        }else{
            file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . 'mode not set' . "\r\n", FILE_APPEND);
            return array('result'=> False, 'message'=> 'mode not set');
        }
    }


    public function query()
    {
        // step2: 私钥签名
        $sign = $this->sign($this->params, $this->priKey);
        
        // step3: 拼接post数据
        $post = array(
            'charset' => 'utf-8',
            'signType' => '01',
            'data' => json_encode($this->params),
            'sign' => $sign
        );

        // step4: post请求
        $result = $this->http_post_json(self::API_HOST . '/order/query', $post);
        $arr = $this->parse_result($result);

        //step5: 公钥验签
        try {
            $this->verify($arr['data'], $arr['sign'], $this->pubKey);
            return $arr['data'];
        } catch (\Exception $e) {
            echo $e->getMessage();
            return $e->getMessage();
            //exit;
        }

    }

    public function orderPay()
    {
            $db =  new DBHandller(); 
            $return = $db->sandpayMerchantMidSearch($this->params['head']['mid']);
            if (! $return['result']) return $return; 

            $sandpayMerchant = $return['data'];
            $this->params['head']['mid'] = $sandpayMerchant->mid;
            $this->CERT_PWD = $sandpayMerchant->certPwd;
         
            // 获取公私钥匙
            try {
                $this->priKey = $this->loadPk12Cert($this->keyPath . $sandpayMerchant->priKey, $this->CERT_PWD);
                //$this->pubKey = $this->loadX509Cert($this->keyPath . $sandpayMerchant->pubKey);
            }catch(\Exception $e){
                return array('result'=> FALSE, 'message'=> $e->getMessage());
            }
            // step2: 私钥签名
            try{
                $sign = $this->sign($this->params, $this->priKey);
            }catch(\Exception $e){
                return array('result'=> False, 'message'=> $e->getMessage()) ;
            }      

            // step3: 拼接post数据
            $post = array(
                'charset' => 'utf-8',
                'signType' => '01',
                'data' => json_encode($this->params),
                'sign' => $sign
            );

            // step4: post请求
            try {
                $result = $this->http_post_json(self::API_HOST . '/order/pay', $post);
            }catch (\Exception $e) {
                return array('result'=> False, 'message'=> $e->getMessage()) ;
                //exit;
            }
            $arr = $this->parse_result($result);
            
            //step5: 公钥验签
            //it will be done on base class constructor $pubkey = $this->loadX509Cert(PUB_KEY_PATH);
            //print_r($this->pubKey);
            try {
                $this->verify($arr['data'], $arr['sign'], $this->pubKey);
            } catch (\Exception $e) {
                return array('result'=> False, 'message'=> $e->getMessage()) ;
                //exit;
            }

            // step6： 获取credential
            $data = json_decode($arr['data'], true);
            if ($data['head']['respCode'] == "000000") {
                $credential = $data['body']['credential'];
                return array('result'=> True, 'data'=> $credential);
            } else {
                //print_r($arr['data']);
                return array('result'=> False, 'message'=>$arr['data']);
            }
    }


    public function notify()
    {
        //step5: 公钥验签
        try {
            $this->verify($this->params['data'], $this->params['sign'], $this->pubKey);
        } catch (\Exception $e) {
            return array('result'=> False, 'message'=> $e->getMessage()) ;
            //exit;
        }

        //$data = stripslashes($this->params['data']); //支付数据
        $data = json_decode(stripslashes($this->params['data']), true); //data数据

        file_put_contents("./test_notifyUrl_log.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . print_r($data, true) . "\r\n", FILE_APPEND);


        
        return 'debug';
        
        try {
            //file_put_contents("./test_notifyUrl_log.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . $data . "\r\n", FILE_APPEND);

            $result = $this->verify($data, $this->params['sign'], $this->pubKey);
            
            //file_put_contents("./test_notifyUrl_log.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . $data . "\r\n", FILE_APPEND);

            return $data;
            //echo "respCode=000000";
        }catch(\Exception $e){
            return array('result' => False, 'message' => $e->getMessage()); 
        }
        
        
    }

}