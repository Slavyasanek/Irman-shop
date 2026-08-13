<?php

namespace CleanTheme\Components;

class Breadcrumbs {

    public static function get_breadcrumb($post) {
        $html = '';
        $position = 2; // Position 1 is reserved for "Головна" (Home)

        // 1. Parent Page Logic
        if (isset($post->post_parent) && $post->post_parent) {
            $html .= '<li class="breadcrumbs__item breadcrumbs__item--link" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            $html .= '<a href="' . esc_url(get_permalink($post->post_parent)) . '" itemprop="item">';
            $html .= '<span itemprop="name">' . esc_html(get_the_title($post->post_parent)) . '</span></a>';
            $html .= '<meta itemprop="position" content="' . $position++ . '" />';
            $html .= '</li>';
        }

        // 2. Product Category Logic (WooCommerce)
        if ($post) {
            $terms = get_the_terms($post->ID, 'product_cat');
            if (!empty($terms) && !is_wp_error($terms)) {
                // We only take the first category to avoid a messy, split breadcrumb trail
                $term = $terms[0];
                $html .= '<li class="breadcrumbs__item breadcrumbs__item--link" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                $html .= '<a href="' . esc_url(get_term_link($term)) . '" itemprop="item">';
                $html .= '<span itemprop="name">' . esc_html($term->name) . '</span></a>';
                $html .= '<meta itemprop="position" content="' . $position++ . '" />';
                $html .= '</li>';
            }
        }

        // 3. Current Item Logic (Category / Single / Page)
        $current_title = '';
        
        if (is_category()) {
            $current_title = single_cat_title('', false);
        } elseif (is_single() || is_page()) {
            $current_title = get_the_title($post->ID);
        }

        if ($current_title) {
            $html .= '<li class="breadcrumbs__item" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            // Final items in breadcrumbs shouldn't be links, just text
            $html .= '<span itemprop="name">' . esc_html($current_title) . '</span>';
            $html .= '<meta itemprop="position" content="' . $position . '" />';
            $html .= '</li>';
        }

        return $html;
    }

    public static function render($post, $className = '') {
        // Fix for the $$post typo and ensuring post exists
        if (!$post && !is_archive()) {
            return '';
        }
        
        ?>
            <nav aria-label="Breadcrumb" class="breadcrumbs text--reg <?= esc_attr($className) ?>">
                <ul class="breadcrumbs__list flex-row" itemscope itemtype="https://schema.org/BreadcrumbList">
                    
                    <li class="breadcrumbs__item breadcrumbs__item--link c--black" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?= esc_url(home_url('/')) ?>" itemprop="item">
                            <span itemprop="name">Головна</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    
                    <?= self::get_breadcrumb($post) ?>
                    
                </ul>
            </nav>
        <?php   
    }
}