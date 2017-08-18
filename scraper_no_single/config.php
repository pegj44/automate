<?php

/**
 * Form fields
 */

// Default required field
add_field('site_url', array(
        'value' => '', 
        'field_type' => 'input', 
        'type' => 'text',
        'placeholder' => 'URL',
        'attr' => array()
    ));

// Default required field
add_field('page_start', array(
        'value' => '', 
        'field_type' => 'input', 
        'type' => 'number',
        'placeholder' => 'Page Start',
        'attr' => array(
            'min' => '1',
            'step' => '1'
        )
    ));

// Default required field
add_field('page_end', array(
        'value' => '', 
        'field_type' => 'input', 
        'type' => 'number',
        'placeholder' => 'Page end',
        'attr' => array(
            'min' => '1',
            'step' => '1'
        )
    ));

/**
 * Url page parameter
 */
$pageSep = '?page=';

/**
 * Item wrapper
 */
$scrape_wrap = '.course-list > div > div';

/**
 * Data to scrape
 * 
 * 0 = Table title
 * 1 = Target class/id/attribute
 * 2 = Output
 */
$scrape = array(
    array('Title', '.course-listing-title', 'plaintext'),
    array('Description', '.course-listing-subtitle', 'plaintext'),
    array('Image', '.course-box-image', 'src'),
    // array('Course Price', '.course-price', 'plaintext')
);

/**
 * General output
 * 
 */
$scrape_out = '

    <div>%data%</div>

';