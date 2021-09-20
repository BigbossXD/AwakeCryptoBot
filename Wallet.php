<?php

  include 'Config.php';

  $date = date_create();
  $ts = date_timestamp_get($date);
  $postData = ['ts'=> $ts];

  $sig = hash_hmac('sha256', json_encode($postData), API_SECRET, false);
  $postData["sig"] = $sig;

  $url = 'https://api.bitkub.com/api/market/wallet';
  $reutrncurl = CurlAPI($url, $postData);
  
  echo '<H1>PHP BITKUB API EXAMPLE</H1>';
  echo 'Nontachai D., 2021<Br><Br>';

  echo '<b>EXAMPLE : /api/market/wallet </b><Br>';
  echo '<b>RETURN FROM API </b><Br>';
  echo '<pre>';
  print_r($reutrncurl);
  echo '</pre>';

  echo '<b>WALLET SYM LIST FROM DATA</b><Br>';
  $MyWallet = json_decode($reutrncurl["responsetext"] );
  $MyWallet = (array) $MyWallet;
  $MyWalletList = (array) $MyWallet['result'];
  echo '<pre>';
  print_r( $MyWalletList );
  echo '</pre>';
  function CurlAPI($url, $jsonOutString)
	{
		$retVal = array();

      $url = $url;
      $timeout = 30;
      $headers[0] = 'Accept: application/json';
      $headers[1] = 'Content-Type: application/json';
      $headers[2] = 'X-BTK-APIKEY: '.API_KEY;

	    $curl = curl_init();

	    curl_setopt($curl, CURLOPT_POST, 1);
	    $timeout_ms = intval(1000*$timeout);
      $jsonOutString = json_encode($jsonOutString);
	    
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_TIMEOUT_MS, $timeout_ms);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonOutString);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	    $resp = curl_exec($curl);

	    $err = curl_error($curl);    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	    if($err){

	        $errno = curl_errno($curl);
	        if($errno == CURLE_OPERATION_TIMEDOUT){
	            $retVal['status'] = "FAIL";    $retVal['description'] = "Timeout";
	            $retVal['httpcode'] = $httpStatus;
	            $retVal['responsetext'] = "";
	        }
	        else{
	            $retVal['status'] = "FAIL";    $retVal['description'] = "Curl status \"".$err."\"";
	            $retVal['httpcode'] = $httpStatus;
	            $retVal['responsetext'] = $resp;
	        }
	        curl_close($curl);
	        unset($curl);        $curl = null;
	        return($retVal);
	    }

	    if($httpStatus != 200){
	        $retVal['status'] = "FAIL";         $retVal['description'] = "HTTP status code ".$httpStatus;
	        $retVal['httpcode'] = $httpStatus;
	        $retVal['responsetext'] = $resp;
	    }
	    else{
	        $retVal['status'] = "SUCCESS";      $retVal['description'] = "HTTP status code ".$httpStatus;
	        $retVal['httpcode'] = $httpStatus;
	        $retVal['responsetext'] = $resp;
	    }

	    curl_close($curl);
	    unset($curl);        $curl = null;

	    return($retVal);
	}

?>