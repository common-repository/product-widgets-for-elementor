<?php

class WCProduct_list_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_list";
    }

    public function get_title()
    {
        return __("WC Product list", "wc_product_list");
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

        $this->add_control(
            'productCount',
            [
                'label' => esc_html__('Product limit', 'miga_wc_elements'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 10,
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__('Show pagination', 'miga_wc_elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'your-plugin'),
                'label_off' => esc_html__('Hide', 'your-plugin'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'use_permalinks',
            [
                'label' => esc_html__('Use permalinks', 'miga_wc_elements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'your-plugin'),
                'label_off' => esc_html__('Hide', 'your-plugin'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'customProducts',
            [
                'label' => esc_html__('Product IDs (with comma)', 'miga_wc_elements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('', 'miga_wc_elements'),
                'placeholder' => esc_html__('Type your ids here', 'miga_wc_elements'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $productCount = $settings["productCount"];
        $customProducts = $settings["customProducts"];
        if (!class_exists('WooCommerce')) {
            return;
        }

        global $wp_query;
        $paged = 1;
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $args=[];
        if (is_product_category()) {
            $args = [ 'status' => 'publish', 'limit' => $productCount, 'paginate' => true, 'page' => $paged, 'category' => get_queried_object()->name, 'orderby' => 'date', 'order' => 'DESC'];
        } else {
            $args = ['status' => 'publish', 'limit' => $productCount, 'paginate' => true, 'page' => $paged, 'orderby' => 'date', 'order' => 'DESC'];
        }

        if (!empty($customProducts)) {
            $customProducts = explode(",", $customProducts);
            $args = ['status' => 'publish', 'include' => $customProducts, 'paginate' => true];
        }
        $products = wc_get_products($args);
        if (isset($products)) {
            echo '<ul class="miga_wc_elements_products">';

            foreach ($products->products as $key => $product) {
                $categories = wc_get_product_category_list($product->get_id());
                $linkUrl = "";

                if ($settings["use_permalinks"] == "yes") {
                    $linkUrl = $product->get_permalink();
                } else {
                    $attrs = $product->get_attributes();
                    foreach ($attrs as $key => $each_download) {
                        if ($each_download["name"] == "url") {
                            $linkUrl = get_site_url().'/'. $each_download["value"];
                        }
                    }
                }


                echo '<li class="wc_product">';
                echo '<a class="wc_product_link" href="'.esc_url($linkUrl).'"></a><div class="wc_product_categories">';
                echo $categories;
                echo '</div><b>'.esc_html($product->get_name()).'</b>';
                echo esc_html(substr(strip_tags($product->get_description()), 0, 100));
                if (strlen($product->get_description())>=100) {
                    echo '...';
                }
                echo '<br/>';
                echo '<a class="wc_product_more" href="'.esc_url($linkUrl).'">details</a>';
                echo '</li>';
            }

            echo '</ul>';

            if ('yes' === $settings['show_pagination']) {
                echo '<div class="miga_wc_elements_products_pagination">';
                $total_pages = $products->max_num_pages;
                echo paginate_links(apply_filters('woocommerce_pagination_args', array(
                        'current'   => max(1, $paged),
                        'total'     => $total_pages,
                        'end_size'  => 3,
                        'mid_size'  => 3,
                    )));
                echo '</div>';
            }
            wp_reset_postdata();
        }
    }

    protected function _content_template()
    {
    }
}
