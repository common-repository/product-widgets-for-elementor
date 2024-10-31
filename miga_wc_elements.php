<?php
/**
 * Plugin Name
 *
 * @package           PluginPackage
 * @author            Michael Gangolf
 * @copyright         2022 Michael Gangolf
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Product Widgets for Elementor
 * Description:       Create simple WooCommerce product detail pages with Elementor.
 * Version:           1.0.8
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Michael Gangolf
 * Author URI:        https://www.migaweb.de/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_action('wp_enqueue_scripts', 'miga_wc_elements_scripts');

function miga_wc_elements_scripts()
{
    wp_register_style('miga_wc_elements_styles', plugins_url('styles/main.css', __FILE__));
    wp_enqueue_style('miga_wc_elements_styles');
}

use Elementor\Plugin;

add_action('init', static function () {
    if (! did_action('elementor/loaded') || !class_exists( 'WooCommerce' )) {
        return false;
    }
    require_once(__DIR__ . '/widget/wc_product_attributes.php');
    require_once(__DIR__ . '/widget/wc_product_downloads.php');
    require_once(__DIR__ . '/widget/wc_product_list.php');
    require_once(__DIR__ . '/widget/wc_product_image.php');
    require_once(__DIR__ . '/widget/wc_product_title.php');
    require_once(__DIR__ . '/widget/wc_product_description.php');
    require_once(__DIR__ . '/widget/wc_product_price.php');
    require_once(__DIR__ . '/widget/wc_product_buy.php');
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_attributes_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_list_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_image_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_title_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_description_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_price_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_buy_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WCProduct_download_Widget());
});


//Removes links
add_filter( 'woocommerce_product_is_visible','miga_wc_elements_product_invisible');
function miga_wc_elements_product_invisible(){
    return false;
}

//Remove single page
add_filter( 'woocommerce_register_post_type_product','miga_wc_elements_hide_product_page',12,1);
function miga_wc_elements_hide_product_page($args){
    $args["publicly_queryable"]=false;
    $args["public"]=false;
    return $args;
}

add_action( 'elementor/widget/archive-posts/skins_init', function( $widget ) {
  if (!class_exists('\ElementorPro\Modules\ThemeBuilder\Skins\Posts_Archive_Skin_Cards')) {
    return;
  }
    class cards_multi_badge_skin extends \ElementorPro\Modules\ThemeBuilder\Skins\Posts_Archive_Skin_Cards {
        protected function render_post_footer() {
            $args = array('taxonomy'     => 'product_cat');

            $all_categories = get_the_terms( get_the_ID(), 'product_cat' );
            if (!empty($all_categories)) {
            ?><div class="elementor-search-categories"><?php

            foreach( $all_categories as $term ) : ?>
                <div class="elementor-search-category"><?php echo $term->name; ?></div>
            <?php endforeach;
            echo '</div>';
          } ?>

          </div>
        </article>
            <?php
        }
    }
    $widget->add_skin( new cards_multi_badge_skin( $widget ) );
} );
