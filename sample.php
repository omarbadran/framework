<?php

/**
 *  initialize options 
 **/
$coraSample = new CoraFramework( array(
    'id'         => 'cora',
    'page_title' => 'Cora Settings',
    'menu_title' => 'Cora',
));


/**
 *  Add sections 
 **/
$coraSample->add_section( array( 
    'id'        =>  'general',
    'title'     =>  'General',
    'icon'      =>  'home',
));

$coraSample->add_section( array( 
    'id'        =>  'header',
    'title'     =>  'Header',
    'icon'      =>  'sort',
));


/**
 *  Add fields 
 **/
$coraSample->add_field( array( 
    'id'        =>  'test_text',
    'section'   =>  'general',
    'type'      =>  'text',
    'title'     =>  'Hello!',
    'default'   =>  'hello world!'
));


// echo '<pre>';
// var_dump( $coraSample );
// echo '</pre>';