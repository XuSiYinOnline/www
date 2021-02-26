<?php


class geoPlugin {
	
	//服务器
	var $host = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}&lang={LANG}';

	
	//默认语言
	var $lang = 'zh-CN';


/*
支持语言:
de
en
es
fr
ja
pt-BR
ru
zh-CN
*/

	//启动变量
	var $ip = null;
	var $city = null;
	var $region = null;
	var $regionCode = null;
	var $regionName = null;
	var $dmaCode = null;
	var $countryCode = null;
	var $countryName = null;
	var $inEU = null;
	var $euVATrate = false;
	var $continentCode = null;
	var $continentName = null;
	var $latitude = null;
	var $longitude = null;
	var $locationAccuracyRadius = null;
	var $timezone = null;
	var $currencyCode = null;
	var $currencySymbol = null;
	var $currencyConverter = null;
	
	function __construct() {

	}
	
	function locate($ip = null) {
		
		global $_SERVER;
		
		if ( is_null( $ip ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$host = str_replace( '{IP}', $ip, $this->host );
		$host = str_replace( '{CURRENCY}', $this->currency, $host );
		$host = str_replace( '{LANG}', $this->lang, $host );
		
		$data = array();
		
		$response = $this->fetch($host);
		
		$data = unserialize($response);
		
		//设置变量
		$this->ip = $ip;
		$this->city = $data['geoplugin_city'];
		$this->region = $data['geoplugin_region'];
		$this->regionCode = $data['geoplugin_regionCode'];
		$this->regionName = $data['geoplugin_regionName'];
		$this->dmaCode = $data['geoplugin_dmaCode'];
		$this->countryCode = $data['geoplugin_countryCode'];
		$this->countryName = $data['geoplugin_countryName'];
		$this->inEU = $data['geoplugin_inEU'];
		$this->euVATrate = $data['euVATrate'];
		$this->continentCode = $data['geoplugin_continentCode'];
		$this->continentName = $data['geoplugin_continentName'];
		$this->latitude = $data['geoplugin_latitude'];
		$this->longitude = $data['geoplugin_longitude'];
		$this->locationAccuracyRadius = $data['geoplugin_locationAccuracyRadius'];
		$this->timezone = $data['geoplugin_timezone'];
		$this->currencyCode = $data['geoplugin_currencyCode'];
		$this->currencySymbol = $data['geoplugin_currencySymbol'];
		$this->currencyConverter = $data['geoplugin_currencyConverter'];
		
	}
	
	function fetch($host) {

		if ( function_exists('curl_init') ) {
						
			//使用cURL获取数据
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'v1.1');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if ( ini_get('allow_url_fopen') ) {
			
			//退回打开
			$response = file_get_contents($host, 'r');
			
		} else {

			trigger_error ('无法检索数据.', E_USER_ERROR);
			return;
		
		}
		
		return $response;
	}
	

	
	function nearby($radius=10, $limit=null) {

		if ( !is_numeric($this->latitude) || !is_numeric($this->longitude) ) {
			trigger_error ('纬度或经度值不正确.', E_USER_NOTICE);
			return array( array() );
		}
		
		$host = "http://www.geoplugin.net/extras/nearby.gp?lat=" . $this->latitude . "&long=" . $this->longitude . "&radius={$radius}&base_currency={CURRENCY}&lang={LANG}";
		
		if ( is_numeric($limit) )
			$host .= "&limit={$limit}";
			
		return unserialize( $this->fetch($host) );

	}

	
}

?>
