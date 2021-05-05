<?php
/**
 * Primary Nav
 * 
 * @author Andrea Musso
 * 
 * @package Foundry
 */

if ( has_nav_menu( 'footerone' ) ) :
    wp_nav_menu([
        'theme_location'    => 'footerone',
        'menu_class'        => 'footer-menu-1',
        'container'         => 'nav',
        'container_class'   => 'footer-menu',
        'depth'             => 1
    ]);
endif;
