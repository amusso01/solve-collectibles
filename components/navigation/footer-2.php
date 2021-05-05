<?php
/**
 * Primary Nav
 * 
 * @author Andrea Musso
 * 
 * @package Foundry
 */

if ( has_nav_menu( 'footertwo' ) ) :
    wp_nav_menu([
        'theme_location'    => 'footertwo',
        'menu_class'        => 'footer-menu-2',
        'container'         => 'nav',
        'container_class'   => 'footer-menu',
        'depth'             => 1
    ]);
endif;
