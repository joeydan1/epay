<?php
namespace App\Providers;

/**
 *	中钢rsa加密
 */
class zgChannelBase
{

    public $result;
    public $message;

	public $privateFile;//私钥
	public $password;
	public $publicFile;//公钥
    public static $BCCOMP_LARGER = 1;
    
	public function __construct($config){
		$this->privateFile	= $config['privateFile'];
		$this->publicFile	= $config['publicFile'];
		
		$this->password	= '12345678';
	}
	

	/**
	 * 
	 * @param  $str 加密前原字符串
	 * @return base 加密后字符串
	 */
	public function encryData($str){	
		$pkcs12certdata = file_get_contents($this->publicFile);
		$public_key_id= openssl_get_publickey($pkcs12certdata); 
		
		
		$split = str_split($str, 100);// 1024bit && OPENSSL_PKCS1_PADDING  不大于117即可
		$rsabin = '';
		foreach ($split as $part) {
			$isOkay = openssl_public_encrypt($part, $en_data, $public_key_id,OPENSSL_PKCS1_PADDING);
			if(!$isOkay){
				return false;
			}
			$rsabin .= $en_data;
			
		}

		$crypted = base64_encode($rsabin);
		return $crypted;
    }
    

	/**
	 * 
	 * @param  $str 加密前原字符串
	 * @return base 加密后字符串
	 */
	public function decryData($str){
		$privatedata = file_get_contents($this->privateFile);
		openssl_pkcs12_read( $privatedata, $certs, $this->password);
		$priKey=openssl_get_privatekey($certs['pkey']);
		echo $priKey;
		
		$data = base64_decode($str);
		//echo '<br>'.$data.'<br>';
		$split = str_split($data, 256);// 1024bit  固定172
		foreach ($split as $part) {
			$isOkay = openssl_private_decrypt($part, $de_data,$priKey);// base64在这里使用，因为128字节是一组，是encode来的
			if(!$isOkay){
				return false;
			}
			$decode_data .= $de_data;
		}
		return $decode_data;
    }
    

	public function arr_to_xml($arr, $dom = 0, $item = 0) {
		if (! $dom) {
			$dom = new DOMDocument ("1.0","utf-8");
		}
		
		if (! $item) {
			$ccc = array_keys ( $arr );
			
			$str_head = 'pay_interaction';
			$item = $dom->createElement ($str_head);
			$dom->appendChild ( $item );
		}
		foreach ( $arr as $key => $val ) {		
			$itemx = $dom->createElement ( is_string ( $key ) ? $key : "record" );
			$item->appendChild ( $itemx );
			if (! is_array ( $val )) {
				$text = $dom->createTextNode ( $val );
				$itemx->appendChild ( $text );
			} else {
				$this->arr_to_xml ( $val, $dom, $itemx );
			}
		}
		
		return $dom->saveXML ();
	}


	public function xml_to_array($xml) {
		$reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
		if (preg_match_all ( $reg, $xml, $matches )) {
			$count = count ( $matches [0] );
			$arr = array ();
			for($i = 0; $i < $count; $i ++) {
				
				$key = $matches [1] [$i];	
				
				$val = $this->xml_to_array ( $matches [2] [$i] ); // 递归
				if (array_key_exists ( $key, $arr )) {
					if (is_array ( $arr [$key] )) {
						if (! array_key_exists ( 0, $arr [$key] )) {
							$arr [$key] = array (
									$arr [$key] 
							);
						}
					} else {
						
						$arr [$key] = array (
								$arr [$key] 
						);
					}
					$arr [$key] [] = $val;
				} else {
					
					$arr [$key] = $val;
				}
			}
			return $arr;
		} else {
			return $xml;
		}
	}
    
    
	public function curl_get($url,$data) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);//附加
		curl_setopt($ch,CURLOPT_TIMEOUT,30);//附加
		$return = curl_exec($ch);
		curl_close($ch);
		return $return;
	}
    
    
	 /***
		@function	curl   post提交方法
		@author		meebill
		@date		2017-11-08
	***/
	public function curl_post($url,$data){
        $data = http_build_query($data);
        
        try{
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false); // 
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            $return = curl_exec($ch);
            curl_close($ch);

            if ($return)throw new \Exception('请求出错');
            return array('result'=> TRUE, 'return'=> urldecode($return));

        }catch(\Exception $e){
            return array('result'=> FALSE, 'message'=> $e->getMessage());
        }
	}
}










?>