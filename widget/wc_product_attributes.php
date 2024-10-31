<?php
class WCProduct_attributes_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_attributes";
    }

    public function get_title()
    {
        return __("WC Github attributes", "wc_attributes");
    }

    public function get_icon()
    {
        return "eicon-accordion";
    }

    public function get_categories()
    {
        return ["general"];
    }

    protected function _register_controls()
    {
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $product_id = null;

        if (!class_exists( 'WooCommerce' )) return;

        if (! empty($settings['product_id'])) {
            $product_id = $settings['product_id'];
        } elseif (wp_doing_ajax()) {
            if (isset($_POST['post_id'])) {
                $product_id = sanitize_key($_POST['post_id']);
            }
        } else {
            $product_id = get_queried_object_id();
        }


        global $product;
        if (isset($product_id)) {
            echo '<ul class="wc_attributes">';
            $product = wc_get_product($product_id);
            $attrs = $product->get_attributes();
            foreach ($attrs as $key => $each_download) {
                if ($each_download["name"] != "github") {
                    echo '<li><b>'.esc_html($each_download["name"]).'</b>: ' . esc_html($each_download["value"]).'</li>';
                }
            }

            echo '</ul>';
            echo '<a class="button" target="_blank" href="'.esc_url($product->get_attribute("github")).'">github link</a>';
        }
    }

    protected function _content_template()
    {
    }
}
