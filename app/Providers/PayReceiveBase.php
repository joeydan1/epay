<?php
namespace App\Providers;

//use App\Providers;

class PayReceiveBase
{

    //const API_HOST = 'https://caspay.sandpay.com.cn/agent-main/openapi/';
    const PUB_KEY_PATH = '//var//www//html//epay//app//Providers//cert//sand.cer'; //公钥文件
   // const PRI_KEY_PATH = '//home//vagrant//code//Laravel/app//Providers//cert//MID_RSA_PRIVATE_KEY_100211701160001.pfx'; //私钥文件
   // const PRI_KEY_PATH = '//var//www//html//epay/app//Providers//cert//privateKey.pfx';
   //const CERT_PWD = '123456'; //私钥证书密码
   // const CERT_PWD = 'gzyf7777'; //私钥证书密码
    

    public $priKey;
    public $pubKey;
    public $params;
    public $mode;
    public $post;
    public $keyPath;
    public $CERT_PWD;


    function __construct()
    {
        $this->keyPath = '//var//www//html//epay//app//Providers//cert//';
        //$this->CERT_PWD = 'gzyf7777';
        // 获取公私钥匙
        //$this->priKey = $this->loadPk12Cert(self::PRI_KEY_PATH, self::CERT_PWD);
        //$priKey_2 = loadPk12Cert(PRI_KEY_PATH_2, CERT_PWD);
        $this->pubKey = $this->loadX509Cert(self::PUB_KEY_PATH);
        //$this->params = GeneralPayReceiveParams::loadParams();
    }


    function loadX509Cert($path)
    {
        try {
            $file = file_get_contents($path);
            if (!$file) {
                throw new \Exception('loadx509Cert::file_get_contents ERROR');
            }

            $cert = chunk_split(base64_encode($file), 64, "\n");
            $cert = "-----BEGIN CERTIFICATE-----\n" . $cert . "-----END CERTIFICATE-----\n";

            $res = openssl_pkey_get_public($cert);
            $detail = openssl_pkey_get_details($res);
            openssl_free_key($res);

            if (!$detail) {
                throw new \Exception('loadX509Cert::openssl_pkey_get_details ERROR');
            }

            return $detail['key'];
        } catch (\Exception $e) {
            throw $e;
        }
    }   



    /**
     * 获取私钥
     * @param $path
     * @param $pwd
     * @return mixed
     * @throws Exception
     */
    function loadPk12Cert($path, $pwd)
    {
        try {
            $file = file_get_contents($path);
            //echo $file;
            
            if (!$file) {
                throw new \Exception('loadPk12Cert::file
                        _get_contents');
            }

            if (!openssl_pkcs12_read($file, $cert, $pwd)) {
                throw new \Exception('loadPk12Cert::openssl_pkcs12_read ERROR');
            }
            return $cert['pkey'];
        } catch (\Exception $e) {
            file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . $e->getMessage() . "\r\n", FILE_APPEND);
            throw $e;
        }
    }



    /**
     * 私钥签名
     * @param $plainText
     * @param $path
     * @return string
     * @throws Exception
     */
    function sign($plainText, $path)
    {
        $plainText = json_encode($plainText);

        try {
            $resource = openssl_pkey_get_private($path);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                throw new \Exception('签名出错' . $plainText);
            }
            return base64_encode($sign);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 公钥验签
     * @param $plainText
     * @param $sign
     * @param $path
     * @return int
     * @throws Exception
     */
    function verify($plainText, $sign, $path)
    {
        $resource = openssl_pkey_get_public($path);   
        $result = openssl_verify($plainText, base64_decode($sign), $resource);
        openssl_free_key($resource);

        if (!$result) {
            throw new \Exception('签名验证未通过,plainText:' . $plainText . '。sign:' . $sign, '02002');
        }

        return $result;
    }

    /**
     * 公钥加密AESKey
     * @param $plainText
     * @param $puk
     * @return string
     * @throws Exception
     */
    function RSAEncryptByPub($plainText, $puk)
    {
        if (!openssl_public_encrypt($plainText, $cipherText, $puk, OPENSSL_PKCS1_PADDING)) {
            throw new \Exception('AESKey 加密错误');
        }

        return base64_encode($cipherText);
    }

    /**
     * 私钥解密AESKey
     * @param $cipherText
     * @param $prk
     * @return string
     * @throws Exception
     */
    function RSADecryptByPri($cipherText, $prk)
    {
        if (!openssl_private_decrypt(base64_decode($cipherText), $plainText, $prk, OPENSSL_PKCS1_PADDING)) {
            throw new \Exception('AESKey 解密错误');
        }

        return (string)$plainText;
    }

    /**
     * AES加密
     * @param $plainText
     * @param $key
     * @return string
     * @throws \Exception
     */
    function AESEncrypt($plainText, $key)
    {
        $plainText = json_encode($plainText);
        $result = openssl_encrypt($plainText, 'AES-128-ECB', $key, 1);

        if (!$result) {
            throw new \Exception('报文加密错误');
        }

        return base64_encode($result);
    }

    /**
     * AES解密
     * @param $cipherText
     * @param $key
     * @return string
     * @throws \Exception
     */
    function AESDecrypt($cipherText, $key)
    {
        $result = openssl_decrypt(base64_decode($cipherText), 'AES-128-ECB', $key, 1);

        if (!$result) {
            throw new \Exception('报文解密错误', 2003);
        }

        return $result;
    }

    /**
     * 生成AESKey
     * @param $size
     * @return string
     */
    function aes_generate($size)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $arr = array();
        for ($i = 0; $i < $size; $i++) {
            $arr[] = $str[mt_rand(0, 61)];
        }

        return implode('', $arr);
    }

    /**
     * 发送请求
     * @param $url
     * @param $param
     * @return bool|mixed
     * @throws Exception
     */
    function http_post_json($url, $param)
    {
        //print_r($param['encryptKey']);

        if (empty($url) || empty($param)) {
            return false;
        }
        $param = http_build_query($param);
        try {
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //正式环境时解开注释
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);

            if (!$data) {
                throw new \Exception('请求出错');
            }

            return $data;
        } catch (\Exception $e) {
            file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . '---------' . "\n" . $e->getMessage() . "\r\n", FILE_APPEND);
            throw $e;
        }
    }


    function parse_result($result)
    {
        $arr = array();
        $response = urldecode($result);
        $arrStr = explode('&', $response);
        foreach ($arrStr as $str) {
            $p = strpos($str, "=");
            $key = substr($str, 0, $p);
            $value = substr($str, $p + 1);
            $arr[$key] = $value;
        }

        return $arr;
    }


}


?>