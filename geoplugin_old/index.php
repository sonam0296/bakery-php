<?php

/***************************************
Author: Davide
Version: V2.0
Update: 27/05/2017 » ipinfodb.com / freegeoip.net added

ipinfodb.com KEY » 7f6583e26ff76bb9fffe42070638c43040c2a5e5e330e81c9a36493a04fc137a

Update: 02/07/2018 » freegeoip.net passou a ipstack.com
ipstack.com KEY » 20317d40e85db6de439f5ee747fce108
***************************************/

// error_reporting(E_ALL); ini_set("display_errors", "1");
error_reporting(0);

function getXml($url, $timeout = 0){
  
  $ch = curl_init($url);

  curl_setopt_array($ch,array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => (int) $timeout
  ));

  if($xml = curl_exec($ch)){
		if(strstr($url, "ipinfodb.com")){
			return $xml;
		}elseif(strstr($url, "ipstack.com")){
			return json_decode($xml);
		}elseif(strstr($url, "geoplugin.net")){
			$xml = @simplexml_load_file($url);
			return $xml;
		}else{
	    	return new SimpleXmlElement($xml);
		}
  }
  else {
    return null;
  }
}

//RETORNA COD PAIS
$ip=$_GET['ip'];

if($ip!=''){

	$status="NOK";
	
	//1 - ipinfodb.com

	//dependendo do tipo, chama um URL especifico
	if(isset($_GET['tipo']) && $_GET['tipo']=="regionName"){
		//$xml = getXml("http://api.ipinfodb.com/v3/ip-city/?key=7f6583e26ff76bb9fffe42070638c43040c2a5e5e330e81c9a36493a04fc137a&ip=".$ip, 2); // 2 second timeout
		$array = explode(";", $xml);
		if($array[0]=='OK'){
			$status='OK';
			$countryCode=$array[3];
			$countryName=$array[4];
			$regionName=$array[5];
		}
	}else{
		//$xml = getXml("http://api.ipinfodb.com/v3/ip-country/?key=7f6583e26ff76bb9fffe42070638c43040c2a5e5e330e81c9a36493a04fc137a&ip=".$ip, 2); // 2 second timeout
		$array = explode(";", $xml);
		if($array[0]=='OK'){
			$status='OK';
			$countryCode=$array[3];
			$countryName=$array[4];
			$regionName="";
		}
	}

	if($status=='OK'){
	
		if(isset($_GET['tipo']) && $_GET['tipo']!=""){
			if($_GET['tipo']=="countryName") echo $countryName;
			if($_GET['tipo']=="regionName") echo $regionName;
		}else{
			echo $countryCode;
		}
		
	//TENTA COM OUTRO SERVIDOR
	}else{

		//2 - ip-api.com
		$xml = getXml("http://ip-api.com/xml/".$ip, 2); // 2 second timeout
		if($xml->status=='success'){
			$status='OK';
		}

		if($status=='OK'){
		
			$countryName=$xml->country;
			$regionName=$xml->regionName;
			$countryCode=$xml->countryCode;
			
			if(isset($_GET['tipo']) && $_GET['tipo']!=""){
				if($_GET['tipo']=="countryName") echo $countryName;
				if($_GET['tipo']=="regionName") echo $regionName;
			}else{
				echo $countryCode;
			}
			
		//TENTA COM OUTRO SERVIDOR
		}else{
		
			//3 - freegeoip.net
			//$xml = getXml("http://freegeoip.net/xml/".$ip, 2); // 2 second timeout
			//$xml = getXml("http://api.ipstack.com/".$ip."?access_key=20317d40e85db6de439f5ee747fce108", 2); // 2 second timeout
			if($xml->country_code!=''){
				$status='OK';
			}

			if($status=='OK'){
			
				$countryName=$xml->country_name;
				$regionName=$xml->region_name;
				$countryCode=$xml->country_code;
				
				if(isset($_GET['tipo']) && $_GET['tipo']!=""){
					if($_GET['tipo']=="countryName") echo $countryName;
					if($_GET['tipo']=="regionName") echo $regionName;
				}else{
					echo $countryCode;
				}
				
			//TENTA COM OUTRO SERVIDOR
			}else{

				//4 - geoplugin.net
				$xml = getXml("http://www.geoplugin.net/xml.gp?ip=".$ip, 2); // 2 second timeout
				if($xml->geoplugin_status>=200 && $xml->geoplugin_status<300){
					$status='OK';
				}

				if($status=='OK'){
					
					$countryName=$xml->geoplugin_countryName;
					$regionName=$xml->geoplugin_regionName;
					$countryCode=$xml->geoplugin_countryCode;
					
					if(isset($_GET['tipo']) && $_GET['tipo']!=""){
						if($_GET['tipo']=="countryName") echo $countryName;
						if($_GET['tipo']=="regionName") echo $regionName;
					}else{
						echo $countryCode;
					}
					
				//QUANDO NÃO HÁ RESPOSTA DE NENHUM DOS SERVIDORES
				}else{
					if(isset($_GET['tipo']) && $_GET['tipo']!=""){
						if($_GET['tipo']=="countryName") echo "Portugal";
						if($_GET['tipo']=="regionName") echo "";
					}else{
						echo "PT";
					}
				}
			}
		}
	}
}

exit();
?>