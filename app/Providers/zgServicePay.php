<?php
namespace App\Providers;
use App\Providers\zgServicePayParams;

class zgServicePay extends zgChannelBase
{
    public function __construct($mode, $post)
    {
        //parent::__construct();
        $this->mode = $mode;
        //$this->post = $post;
        $this->paramsObj = new zgServicePayParams($mode, $post);   
        
    }

    public function execute(){
        switch($this->mode){
            case 'servicePay':
                return $this->servicePay();
                break;
            default:
                return array('result' => FALSE, 'message'=> 'no matched mode found');
        }

    }

    public function servicePay(){

        if (!$this->paramsObj->result){
            return array('result'=> $this->paramsObj->result, 
                         'message'=> $this->paramsObj->message);}
            
        $params = $this->paramsObj->params;
        $post['partner_id'] = $params['partner_id'];
        $post['service_name'] = $params['service_name'];
        $post['rsamsg'] =  $params['rsamsg'];
        $post['md5msg'] =  $params['md5msg'];
        $post['version'] = $params['version'];
        
        $return = curl_post($params['remote_url'],$post);//post提交方法

        return $return;
        
        //if(!$return['result']){
        //    return array($return['result'], $return['message']);
        //}

        //$this->result = TRUE;
        //$this->return = $return['return'];
    
    }



	
 



}










?>