<?php

/**
 *  initialize options 
 **/
$coraSample = new CoraFramework( array(
    'id'         => 'cora',
    'page_title' => 'Cora Settings',
    'menu_title' => 'Cora',
    'display_version' => 'v1.0.0'
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
));

$coraSample->add_field( array( 
    'id'        =>  'color',
    'section'   =>  'general',
    'type'      =>  'color',
    'title'     =>  'Color',
    'condition' => array( 'select', '===' , 'elon')
));

$coraSample->add_field( array( 
    'id'        =>  'textarea',
    'section'   =>  'general',
    'type'      =>  'textarea',
    'title'     =>  'Textarea',
    'placeholder' => 'Type here ...'
));

$coraSample->add_field( array( 
    'id'        =>  'switch',
    'section'   =>  'general',
    'type'      =>  'switch',
    'title'     =>  'Switch',
));

$coraSample->add_field( array( 
    'id'        =>  'select',
    'section'   =>  'general',
    'type'      =>  'select',
    'title'     =>  'Select',
    'condition' => array( 'switch', '===' , true),
    'multiple'  => true,
    'options'   =>   array(
        array(
            'id'   =>  'sam',
            'text' =>  'Sam <i>Harris</i>'
        ),
        array(
            'id'   =>  'elon',
            'text' =>  'Elon Musk'
        ),
    ),
));

$coraSample->add_field( array( 
    'id'        =>  'icon',
    'section'   =>  'general',
    'type'      =>  'icon',
    'title'     =>  'Icon',
));

$coraSample->add_field( array( 
    'id'        =>  'repeater',
    'section'   =>  'header',
    'type'      =>  'repeater',
    'title'     =>  'Repeater',
    'item_title' => 'text',
    'fields'    =>  array(
        array( 
            'id'        =>  'text',
            'type'      =>  'text',
            'title'     =>  'Text',
            'default'   =>  'Hello world',
            'placeholder' => 'Type here ...'
        ),
        array( 
            'id'        =>  'Icon',
            'type'      =>  'icon',
            'title'     =>  'Icon',
            'condition' => array( 'text', '===' , 'semsem'),
        ),
        array( 
            'id'        =>  'editor',
            'section'   =>  'general',
            'type'      =>  'editor',
            'title'     =>  'editor',
        )
    ),
));

$coraSample->add_field( array( 
    'id'        =>  'editor',
    'section'   =>  'general',
    'type'      =>  'editor',
    'title'     =>  'editor',
));

$coraSample->add_field( array( 
    'id'        =>  'export',
    'section'   =>  'toolbar',
    'type'      =>  'export',
    'title'     =>  'export',
));

$coraSample->add_field( array( 
    'id'        =>  'import',
    'section'   =>  'toolbar',
    'type'      =>  'import',
    'title'     =>  'import',
));

$coraSample->add_field( array( 
    'id'        =>  'demo',
    'section'   =>  'toolbar',
    'type'      =>  'demo',
    'title'     =>  'demo',
    'options'   => array(
        array(
            'title' => 'demo 1',
            'img'   => 'http://localhost/wp-content/themes/twentynineteen/screenshot.png',
            'data'  => array(
                'general'   =>  array(
                    'text'  =>  'Hello demo one :)'
                )
            )
        ),
        array(
            'title' => 'demo 2',
            'img'   => 'http://localhost/wp-content/themes/twentysixteen/screenshot.png',
            'data'  => array(
                'general'   =>  array(
                    'text'  =>  'Hello demo two :)'
                )
            )
        ),
    )
));

// echo '<pre>';
// var_dump( $coraSample );
// echo '</pre>';