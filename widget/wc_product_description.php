<?php

class WCProduct_description_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_description";
    }

    public function get_title()
    {
        return __("WC Product Description", "wc_product_description");
    }

    public function get_icon()
    {
        return "eicon-product-description";
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
            'desc_type',
            [
                'label' => esc_html__('Description Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default'  => esc_html__('Default', 'textdomain'),
                    'short' => esc_html__('Short', 'textdomain'),
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
              'name' => 'content_typography',
              'selector' => '{{WRAPPER}} .miga_wc_description',
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
                    '{{WRAPPER}} .miga_wc_description' => 'text-align: {{VALUE}};',
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

        echo '<div class="miga_wc_description">';
        if ($settings["desc_type"] == "short") {
            echo wp_kses_post($product->get_short_description());
        } else {
            echo wp_kses_post($product->get_description());
        }
        echo '</div>';
    }

    protected function _content_template()
    {
    }
}
