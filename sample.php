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
    'id'        =>  'text',
    'section'   =>  'general',
    'type'      =>  'text',
    'title'     =>  'Text',
    'default'   =>  'Hello world',
    'placeholder' => 'Type here ...'
));

$coraSample->add_field( array( 
    'id'        =>  'color',
    'section'   =>  'general',
    'type'      =>  'color',
    'title'     =>  'Color',
    'default'   =>  '#ffffff',
));

$coraSample->add_field( array( 
    'id'        =>  'textarea',
    'section'   =>  'general',
    'type'      =>  'textarea',
    'title'     =>  'Textarea',
    'default'   =>  'Hello world',
    'placeholder' => 'Type here ...'
));

$coraSample->add_field( array( 
    'id'        =>  'switch',
    'section'   =>  'general',
    'type'      =>  'switch',
    'title'     =>  'Switch',
    'default'   =>  true,
));

// echo '<pre>';
// var_dump( $coraSample );
// echo '</pre>';