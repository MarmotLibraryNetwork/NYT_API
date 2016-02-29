<?php 
	/***************************************
	 * Simple class to retrieve feed of NYT best sellers
	 * documentation:
	 * http://developer.nytimes.com/docs/read/best_sellers_api
	 * 
	 * Last Updated: 2016-02-26 JN
	 ***************************************
	*/

class NYT_API {
    
    const BASE_URI = 'http://api.nytimes.com/svc/books/v2/lists/';
    protected $api_key;
    
    public function __construct($key) {
        $this->api_key = $key;
    }
    
    protected function build_url($list_name) {
        $url = self::BASE_URI . $list_name;
        $url .= '?api-key=' . $this->api_key;
        return $url;
    }

    public function get_list($list_name) {
        $url = $this->build_url($list_name);
        /*
        // super fast and easy way, but not as many options
        $response = file_get_contents($url);
        */

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

}

?>
