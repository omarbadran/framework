<?php 
return array(
    'id'         => 'cora',
    'page_title' => 'Cora Settings',
    'menu_title' => 'Cora',

    'sections' => array(

        // General
        array(
            'title'  => 'General',
            'icon'   => 'home',
            'fields' =>  array(

                // Text field test
                array(
                    'id' => 'test_text',
                    'title' => 'Hello!',
                    'default' => 'default value :)'
                )

            )
        ),
    
    )
);