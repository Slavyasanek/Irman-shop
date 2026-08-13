<?php
/**
 * Shop breadcrumb override
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! empty( $breadcrumb ) ) {

    // Using your exact wrapper classes. Added 'container' assuming it matches your layout needs.
    echo '<nav aria-label="Breadcrumb" class="breadcrumbs text--reg container">';
    echo '<ul class="breadcrumbs__list flex-row" itemscope itemtype="https://schema.org/BreadcrumbList">';

    $position = 1;

    foreach ( $breadcrumb as $key => $crumb ) {
        
        $is_last = ( sizeof( $breadcrumb ) === $key + 1 );

        if ( $is_last ) {
            // Final item: The current page (No link, just text, includes aria-current="page")
            echo '<li class="breadcrumbs__item" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html( $crumb[0] ) . '</span>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            
        } elseif ( ! empty( $crumb[1] ) ) {
            // Standard linked item (Home, Shop, Categories, etc.)
            echo '<li class="breadcrumbs__item breadcrumbs__item--link c--black" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . esc_url( $crumb[1] ) . '" itemprop="item">';
            echo '<span itemprop="name">' . esc_html( $crumb[0] ) . '</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            
        } else {
            // Fallback for unlinked items in the middle of the trail (rare, but good practice)
            echo '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html( $crumb[0] ) . '</span>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
        }

        $position++;
    }

    echo '</ul>';
    echo '</nav>';
}