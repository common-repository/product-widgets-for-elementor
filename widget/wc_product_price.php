<?php

class WCProduct_price_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_price";
    }

    public function get_title()
    {
        return __("WC Product Price", "wc_product_price");
    }

    public function get_icon()
    {
        return "eicon-product-price";
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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typographya',
                'selector' => '{{WRAPPER}} .miga_wc_price',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
              'name' => 'content_typographyb',
              'selector' => '{{WRAPPER}} .miga_wc_price',
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .miga_wc_price' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        if (!class_exists( 'WooCommerce' )) return;
        $product = wc_get_product($settings["product_id"]);
        if (!isset($product) || empty($product)) {
          echo "<b> - please select a product - </b>";
          return;
        }

        echo '<div class="miga_wc_price">';
        if ($product->get_regular_price() != $product->get_price()) {
            echo '<strike>'.esc_html($product->get_regular_price().get_woocommerce_currency_symbol()). "</strike> ";
        }
        echo esc_html($product->get_price().  get_woocommerce_currency_symbol());
        echo '</div>';
    }

    protected function _content_template()
    {
    }
}
