<?php

class WCProduct_title_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_title";
    }

    public function get_title()
    {
        return __("WC Product Title", "wc_product_title");
    }

    public function get_icon()
    {
        return "eicon-product-title";
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
            'tag',
            [
                'label' => esc_html__('HTML Tag', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1'  => esc_html__('H1', 'textdomain'),
                    'h2' => esc_html__('H2', 'textdomain'),
                    'h3' => esc_html__('H3', 'textdomain'),
                    'h4' => esc_html__('H4', 'textdomain'),
                    'h5' => esc_html__('H5', 'textdomain'),
                    'b' => esc_html__('b', 'textdomain'),
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
              'name' => 'content_typography',
              'selector' => '{{WRAPPER}} .miga_wc_title',
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
                    '{{WRAPPER}} .miga_wc_title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

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
        $htmltag = "h3";
        if (in_array(esc_attr($settings["tag"]), ['h1','h2','h3','h4','b'])) {
            $htmltag = esc_attr($settings["tag"]);
        }

        if (!isset($product) || empty($product)) {
          echo "<b> - please select a product - </b>";
          return;
        }
        echo '<'.esc_attr($htmltag).' class="miga_wc_title">';
        echo esc_html($product->get_name());
        echo '</'.esc_attr($htmltag).'>';
    }

    protected function _content_template()
    {
    }
}
