<?php

class WCProduct_download_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_downloads";
    }

    public function get_title()
    {
        return __("WC Download button", "wc_downloads");
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
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'miga_wc_elements'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        require("editor_list_products.php");


        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        if (!class_exists( 'WooCommerce' )) return;
        if (! empty($settings['product_id'])) {
            $product_id = $settings['product_id'];
        } elseif (wp_doing_ajax()) {
            if (isset($_POST['post_id'])) {
                $product_id = sanitize_key($_POST['post_id']);
            }
        } elseif (!empty($settings["product_id"])) {
            $product_id = $settings["product_id"];
        } else {
            $product_id = get_queried_object_id();
        }

        global $product;
        if (isset($product_id)) {
            $product = wc_get_product($product_id);

            if (!isset($product)  || empty($product)) {
                return;
            }
            $downloads = $product->get_downloads();
            echo '<div class="miga_wc_elements_downloads">';

            foreach ($downloads as $key => $each_download) {
                if (!empty($each_download["file"])) {
                    echo '<a class="button" href="'.esc_url(get_permalink().$each_download["file"]).'">Download '.esc_html($each_download["name"]).'</a>';
                }
            }
            echo '</div>';
        }
    }

    protected function _content_template()
    {
    }
}
