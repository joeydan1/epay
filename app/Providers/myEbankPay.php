<?php
/*网银支付*/
namespace App\Providers;

class myEbankPay extends myEbankPayBase
{
    public function __construct($mode, $post)
    {
        //parent::__construct();
        $this->mode = $mode;
        $this->post = $post;
        //$this->params = myEbankPayParams::loadParams($mode, $post);
    }

    function execute(){
        if (isset($this->mode)){
            switch($this->mode){
                case 'tradingRecordInsert';
                    return $this->tradingRecordInsert();
                    break;
            }
        }else{
            echo 'mdoe not set';
            return 'mode not set';
        }

    }

    function tradingRecordInsert(){

        $db = new DBHandller();
        $extraObj = json_decode(stripslashes($this->post['extra']));
        //$signObj = json_decode(stripslashes($this->post['sign']));
        
        $result = $db->myPayKey($extraObj->{'memberId'});

        if ($result['result']){
            $myPayKey = $result['myPayKey'];
        }else{
            return $result;
        }
        
        
        $data = array();
        $data["tradeType"] = $this->post['tradeType'];//交易类型
        $data["version"] = $this->post['version'];//版本号
        $data["channel"] = $this->post["channel"];//支付渠道
        $data["mchNo"] = $this->post["mchNo"];//商户号
        $data["body"] = $this->post["body"];//商品描述
        $data["mchOrderNo"] = $this->post["mchOrderNo"];//商户支付订单号
        $data["amount"] = $this->post['amount'];//交易金额，要转string类型
        $data['currency'] = $this->post['currency'];//货币类型
        $data["timePaid"] = $this->post["timePaid"];//订单提交支付时间
        $data["remark"] = $this->post["remark"];//支付描述
        //$data["timeExpire"]=date("YmdHis",strtotime( "+30 seconds"));
        
        $extra = array();
        foreach ($extraObj as $key => $value){
            $extra[$key] = $value;
        }
        
        
        $signStr = array_merge($data, $extra);

        $mySign = $this->formatRequestParams($signStr, $myPayKey);

        if ($this->post['sign'] == $mySign){
            return $db->myEbankPayTradingRecordInsert($signStr);
            //return array('result' => False, 'message' => 'sign verification failed');
        }
        else{
            return array('result' => False, 'message' => 'sign verification failed');
        }
    
    }
}