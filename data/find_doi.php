<?php

// should be a reconcilaiiton service

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/fingerprint.php');
require_once (dirname(__FILE__) . '/lcs.php');


// Match references to DOIs
function match($text)
{
	global $config;
	
	$hits = array();
	
	$post_data = array();
	$post_data[] = $text;
	
	$ch = curl_init(); 
	
	$url = 'http://search.crossref.org/links';
	
	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

	// Set HTTP headers
	$headers = array();
	$headers[] = 'Content-type: application/json'; // we are sending JSON
	
	// Override Expect: 100-continue header (may cause problems with HTTP proxies
	// http://the-stickman.com/web-development/php-and-curl-disabling-100-continue-header/
	$headers[] = 'Expect:'; 
	curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
	
	if ($config['proxy_name'] != '')
	{
		curl_setopt($ch, CURLOPT_PROXY, $config['proxy_name'] . ':' . $config['proxy_port']);
	}

	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
	
	$response = curl_exec($ch);
	
	$obj = json_decode($response);
	if (count($obj->results) == 1)
	{
		if ($obj->results[0]->match)
		{
			// to do: double check
			$doi = str_replace('http://dx.doi.org/', '', $obj->results[0]->doi);
			
			// unpack metadata 
			$parts = explode('&', html_entity_decode($obj->results[0]->coins));
			$kv = array();
			foreach( $parts as $part)
			{
			  list($key, $value) = explode('=', $part);
	  
			  $key = preg_replace('/^\?/', '', urldecode($key));
			  $kv[$key][] = trim(urldecode($value));
			}				
							
			$hit = new stdclass;
			$hit->id 	= $doi;
			
			if (isset($kv['rft.atitle']))
			{
				$hit->name 	= $kv['rft.atitle'][0];
			}
			else
			{
				$hit->name 	= $doi;
			}				
			
			$hit->score = $obj->results[0]->score;
			$hit->match = true;
			$hits[] = $hit;
		}
	}
}			


$filename = 'references.tsv';
$file_handle = fopen($filename, "r");


while (!feof($file_handle)) 
{
	$line = fgets($file_handle);
	
	$parts = explode("\t", $line);
	
	$id = $parts[0];
	$ref = $parts[1];
	
	$hits = match($ref);
	
	if (count($hits) > 0)
	{
		foreach ($hits as $hit)
		{
			if ($hit->match)
			{
				echo 'UPDATE `references` SET doi="' . $hit->doi . '";' . "\n";
			}
		}
	}	
}

?>