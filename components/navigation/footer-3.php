<?php
/**
 * Primary Nav
 * 
 * @author Andrea Musso
 * 
 * @package Foundry
 */

if ( has_nav_menu( 'footerthree' ) ) :
    wp_nav_menu([
        'theme_location'    => 'footerthree',
        'menu_class'        => 'footer-menu-3',
        'container'         => 'nav',
        'container_class'   => 'footer-menu',
        'depth'             => 1
    ]);
endif;
