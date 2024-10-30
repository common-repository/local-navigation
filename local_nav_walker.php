<?php

class local_nav_walker extends Walker_Nav_Menu {


    function start_el( &$output, $item, $depth, $args ) {
        global $wp_query;

        // build html
        $output .= '<li id="nav-menu-item-'. $item->ID . '">';
        $item->url = get_permalink($item->ID);
        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        //get custom meta title if exists


        $title = get_post_meta($item->ID, 'local-nav-menu-text', true);

        $urlTitle = (isset($title) && $title != '' ? $title : $item->post_title);

        $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
          $args->before,
          $attributes,
          $args->link_before,
          apply_filters( 'the_title', $urlTitle, $item->ID ),
          $args->link_after,
          $args->after
        );

        // build html
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}