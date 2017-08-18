<?php

include 'simple_html_dom.php';

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
  )
);

$context = stream_context_create($options);

get_contents();

function get_contents() {
  
    global $context;

    // $theUrl = rtrim('https://www.effectio.org/shop/', "/");
    $theUrl = rtrim( $_POST['site_url'], "/");
    
    $curr_page  = ($_POST['curr_page'] > 1)? (int) $_POST['curr_page'] : 1;

    $total_page = (int) $_POST['total_page'];    
    $shop_items = (isset($_POST['shop_items']))? $_POST['shop_items'] : array();

    $status = '';
    $data = array();

// echo "<pre>";

    /*
     * Curl single item
     */    

        if( !empty($shop_items) ) {

            $single_url = $shop_items[0];

            unset($shop_items[0]);
            $shop_items = array_values($shop_items);
            
            $html = file_get_html( $single_url, 0, $context );

            $title = $html->find('.course-title');
            $subtitle  = $html->find('.course-subtitle');
            $desc  = $html->find('.course-description');
            $curri = $html->find('.curriculum');
            // $salep = $html->find('del .woocommerce-Price-amount');
            // $price = $html->find('ins .woocommerce-Price-amount');


            $title = (isset($title[0]))? trim( $title[0]->plaintext ) : '';
            $subtitle  = (isset($subtitle[0]))? $subtitle[0]->innertext : '';

            $desc  = (isset($desc[0]))? $desc[0]->innertext : '';

            $curri  = (isset($curri[0]))? $curri[0]->innertext : '';



            $data['details'] = array(
                'item_title' => $title,
                'subtitle' => $subtitle,
                'desc'  => htmlspecialchars($desc),
                'curri' => htmlspecialchars($curri),
            );

            $status = 'Scraping';
        } else {

            /*
             * Curl shop items
             */

            $theUrl = $theUrl .'?page='. $curr_page;
            $shop_items = get_shop_contents($theUrl);        

            if( $curr_page > $total_page ) {
                $status = 'Complete';
            } else {
                $status = 'Next page';
            }

            $curr_page += 1;
                
            $data['details'] = array();    

        }        

    $data['curr_page'] = $curr_page;    
    $data['total_page'] = $total_page;
    $data['shop_items'] = $shop_items;
    $data['scrap_status'] = $status;
    // $data['data_arr'] = $data;

    // var_dump($data);

    echo json_encode($data);

// echo "</pre>";       
}

function get_shop_contents($theUrl) {

    global $context;

    $shop_items = array();

    $html = file_get_html($theUrl, 0, $context);

     // get item loop url
    $urls = $html->find('a[data-role="course-box-link"]');

    if( !empty($urls) ) {

        foreach ($urls as $url) {
            $shop_items[] = 'http://optiontiger.teachable.com/'. $url->attr['href'];
        }
    }   
    
    return $shop_items; 
}