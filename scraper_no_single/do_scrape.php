<?php

include 'simple_html_dom.php';
include_once 'functions.php';
include_once 'config.php';

// RUN
// do_scrape();
get_contents();

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
  )
);

$context = stream_context_create($options);

$GLOBALS['scrap_status'] = '';

function do_scrape() {

    global $status;

    $currPage = current_page();

    if( $currPage > $_POST['page_end'] ) {
        $data['details'] = '';
        $data['pageNext'] = $currPage;    
        $data['scrap_status'] = 'Complete';
    } else {
        $data['details'] = get_contents();
        $data['pageNext'] = $currPage;    
        $data['scrap_status'] = $status;
    }

    echo json_encode($data);
}

function get_contents() {

    global $context, $scrape, $scrape_wrap, $status;    

    // $url  = get_url();
    // $html = file_get_html( $url, 0, $context );
    $html = file_get_html( 'http://optiontiger.teachable.com/?page=3', 0, $context );
    $html = $html->find($scrape_wrap, 0);

    var_dump($html);

        foreach($html as $items) {

            $data = array();
            $item = array();
            // foreach ($scrape as $scrapeItems) {
            //     $item[] = $items->children(1);
            // }
            
            var_dump($items);
        }    

    // var_dump($item);

    die();

    $data_items = array();

    if( $html ) {

        foreach($html as $items) {

            $data = array();
            $item = array();
            foreach ($scrape as $scrapeItems) {
                $item[] = array(
                    'data_title' => $scrapeItems[0],
                    'data_value' => trim(preg_replace('/&#36;/', '', $items->find($scrapeItems[1], 0)->$scrapeItems[2]))                
                );            
            }
            
            $data_items[] = $item;
        }       

        $status = 'Success page: '. current_page();
        return $data_items;

    } else {

        $status = 'Error page: '. current_page();
        return 'Error';
    }    
    
}

function get_url() {

    global $pageSep, $status;

    $url = $_POST['site_url'];
    $url = rtrim($url, '/') . '/';
    $pageNum = current_page();

    if( $pageNum > 1 ) {
        $url = $url . $pageSep . $pageNum;    
        $status = 'Scraping page '. current_page();        
    }

    return $url;
}

function scrape_complete() {

    global $status;

    $status = 'Complete';

    return false;

    die();
}

function current_page() {

    $pageNext = $_POST['pageNext'];
    $pageNum  = $_POST['page_start'];    

    if( $pageNext > 0 ) {
        return $pageNext;
    }

    return $pageNum;
}