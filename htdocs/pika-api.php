<?php

/**
 *
 *
 * @category NY Times API Project
 * @author: Pascal Brammeier
 * Date: 3/8/2016
 *
 */
class Pika_API
{

	public
		$base_url = '',
		$search_api_url = '';


	function __construct()
	{

//		$this->base_url = 'opac.marmot.org';

		// Set Base URL
		global $config;
		$this->base_url = $config['pika_url'];

		$this->search_api_url = $this->base_url . '/API/SearchAPI';
	}

	public function do_curl($url)
	{

		// array of request options
		$curl_opts = array(
			// set request url
			CURLOPT_URL => $url,
			// return data
			CURLOPT_RETURNTRANSFER => 1,
			// do not include header in result
			CURLOPT_HEADER => 0,
			// set user agent
			CURLOPT_USERAGENT => 'Pika app cURL Request'
		);

		// Get cURL resource
		$curl = curl_init();
		// Set curl options
		curl_setopt_array($curl, $curl_opts);
		// Send the request & save response to $response
		$response = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		// return respone
		return $response;
	}

		function getTitlebyISBN($isbn){
			$url = $this->search_api_url . '?method=getTitleInfoForISBN'
				. '&isbn='. $isbn ;

			$response = $this->do_curl($url);

			return $response;
	}


}