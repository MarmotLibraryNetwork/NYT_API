<?php 
	/***************************************
	 * Simple class to display feed of NYT best sellers
	 * documentation:
	 * http://developer.nytimes.com/docs/read/best_sellers_api
	 * 
	 * Last Updated: 2016-02-26 JN
	 ***************************************
	*/

class Display_NYT_Lists {
    
    const LIST_MENU = 'names';
    protected $api;
    protected $list_name;
    
    public function __construct(NYT_API $api) {
        $this->api = $api;
        $this->set_list_name();
    }
    
    protected function set_list_name() {
        if ( empty($_GET['list']) ) {
            $this->list_name = self::LIST_MENU;
        } else {
            $this->list_name = $_GET['list'];
        }
    }
    
    protected function get_list_data(){
        $api = $this->api;
        $list = $this->list_name;
        $json = $api->get_list($list);
        $array = json_decode($json, true);
        return $array['results'];
    }

    protected function get_pika_link($isbn) {
      require_once 'pika-api.php';
      $pikaHandler = new Pika_API();
	    $response = $pikaHandler->getTitlebyISBN($isbn);
	    if (!empty($response)) {
		    $links = array();
		    $results = json_decode($response, true);
		    foreach ($results['result'] as $resultItem) {
			    if (!empty($resultItem['id'])) {
				    $links[] = 'http://' . $pikaHandler->base_url . '/GroupedWork/' . $resultItem['id'] . '/Home';
				    //TODO: set protocol dynamically
				    }
		    }
		    return $links;
	    } else {
		    return false;
	    }
    }

    protected function output_menu(array $lists){
        echo('<h2>NYT Lists Menu</h2>');
        echo('<ul class="menu">');
        foreach ($lists as $list) {
            echo('<li>');
            echo('<a href="?list=' . $list['list_name_encoded'] . '">');
            echo($list['display_name']);
            echo('</a>');
            echo('</li>');
        }
        echo('</ul>');
    }

    protected function output_list(array $items){
        $display_name = $items[0]['display_name'];
        echo('<h2>' . $display_name . '</h2>');
        echo('<ul class="list">');
        foreach ($items as $item) {
            $details = $item['book_details'][0]; 
            $src = $details['book_image'];
            $url = $details['amazon_product_url'];
            echo('<li>');
            echo('<img src="' . $src . '" />');
            echo('<div class="title">' . $details['title'] . '</div>');
            echo('<div class="author">by ' . $details['author'] . '</div>');
            echo('<div class="description">' . $details['description'] . '</div>');

	        if (!empty($item['pika_links'])) {
		        echo '<div class="library_links">';
		        foreach ($item['pika_links'] as $link) {
			        echo '<a href="' . $link . '">Find it at the Library now</a><br>';
		        }
		        echo '</div>';
	        }

            echo('<a href="' . $url . '">Buy it now</a>');
            echo('</li>');
        }
        echo('</ul>');
        echo('<p><a href="/menu">Return to menu</a></p>');
    }
    
    public function ouput(){  
        $list_data = $this->get_list_data();
        if ( $this->list_name == self::LIST_MENU ) {
            $this->output_menu($list_data);
        } else {

	        foreach( $list_data as $i => $list_item) {
		        // go through each list item
		        if (!empty($list_item['isbns'])) {
			        foreach ($list_item['isbns'] as $isbns) {
				        $isbn = empty($isbns['isbn13']) ? $isbns['isbn10'] : $isbns['isbn13'];
				        if ($isbn) {
					        $pika_link = $this->get_pika_link($isbn);
					        if ($pika_link) {
						        $list_data[$i]['pika_links'] = $pika_link;
						        break;
					        }
				        }
			        }
		        }
	        }




            $this->output_list($list_data);
        }
    }

}

?>
