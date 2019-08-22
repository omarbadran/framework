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

$coraSample->add_section( array( 
    'id'        =>  'toolbar',
    'title'     =>  'Toolbar',
    'icon'      =>  'layers',
));

/**
 *  Add fields 
 **/
$coraSample->add_field( array( 
    'id'        =>  'test_text',
    'section'   =>  'general',
    'type'      =>  'text',
    'title'     =>  'Hello',
    'default'   =>  'Hello world !',
    'placeholder' => 'Placeholder Texst ...'
));


// echo '<pre>';
// var_dump( $coraSample );
// echo '</pre>';