<?php
/*代收付 */
namespace App\Providers;
//require_once ('GeneralPayReceiveParams.php');

class GeneralPayReceive extends PayReceiveBase
{
    /*production*/
    const API_HOST = 'https://caspay.sandpay.com.cn/agent-main/openapi/';
    /*test*/
    //const API_HOST = 'https://61.129.71.103:7970/agent-main/openapi/';

    public function __construct($mode,$post) 
    {
        parent::__construct();
        $this->mode = $mode;
        $this->params = GeneralPayReceiveParams::loadParams($mode, $post);
    }


    public function execute()
    {
        // step1: 拼接报文及配置
        $transCode = $this->params['transCode']; // 交易码
        $accessType = '0'; // 接入类型 0-商户接入，默认；1-平台接入
        $merId = $this->params['merId']; // 此处更换商户号
        $path = $this->params['url']; // 服务地址
        $pt = $this->params['pt']; // 报文

        // step2: 生成AESKey并使用公钥加密
        $AESKey = $this->aes_generate(16);
        $encryptKey = $this->RSAEncryptByPub($AESKey, $this->pubKey);
       
        // step3: 使用AESKey加密报文
        $encryptData = $this->AESEncrypt($pt, $AESKey);

        // step4: 使用私钥签名报文
        $sign = $this->sign($pt, $this->priKey);

        // step5: 拼接post数据
        $post = array(
            'transCode' => $transCode,
            'accessType' => $accessType,
            'merId' => $merId,
            'encryptKey' => $encryptKey,
            'encryptData' => $encryptData,
            'sign' => $sign
        );

        print_r(self::API_HOST . $path);

        // step6: post请求
        $result = $this->http_post_json(self::API_HOST . $path, $post);
        parse_str($result, $arr);

        try {
            // step7: 使用私钥解密AESKey
            $decryptAESKey = $this->RSADecryptByPri($arr['encryptKey'], $this->priKey);

            // step8: 使用解密后的AESKey解密报文
            $decryptPlainText = $this->AESDecrypt($arr['encryptData'], $decryptAESKey);

            // step9: 使用公钥验签报文
            $this->verify($decryptPlainText, $arr['sign'], $this->pubKey);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $decryptPlainText;
    }
    

}