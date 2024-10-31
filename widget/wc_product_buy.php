<?php

class WCProduct_buy_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_buy";
    }

    public function get_title()
    {
        return __("WC Product Buy Button", "wc_product_buy");
    }

    public function get_icon()
    {
        return "eicon-product-add-to-cart";
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

        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__('Button text', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Buy', 'textdomain'),
                'placeholder' => esc_html__('Type your title here', 'textdomain'),
            ]
        );

        $this->add_control(
            'btn_align',
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
                    '{{WRAPPER}} .miga_wc_buy_container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style Section', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__('Background', 'textdomain'),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .miga_wc_buy',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
              'name' => 'content_typography1',
              'selector' => '{{WRAPPER}} .miga_wc_buy',
            ]
        );

        $this->add_control(
            'text_color1',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .miga_wc_buy' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__('Hover', 'textdomain'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background_hover',
                'label' => esc_html__('Background', 'textdomain'),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .miga_wc_buy:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
              'name' => 'content_typography2',
              'selector' => '{{WRAPPER}} .miga_wc_buy:hover',
            ]
        );

        $this->add_control(
            'text_color2',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .miga_wc_buy:hover' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        if (!class_exists('WooCommerce')) {
            return;
        }

        $product = wc_get_product($settings["product_id"]);

        if (!isset($product) || empty($product)) {
            echo "<b> - please select a product - </b>";
            return;
        }

        echo '<div class="miga_wc_buy_container"><a class="miga_wc_buy elementor-button" href="'.esc_url(wc_get_cart_url()).'?add-to-cart='.esc_attr($product->get_id()).'&quantity=1">'.esc_html($settings["btn_text"]).'</a></div>';
    }

    protected function _content_template()
    {
    }
}
