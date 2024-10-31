<?php

class WCProduct_image_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "wc_product_image";
    }

    public function get_title()
    {
        return __("WC Product Image", "wc_product_image");
    }

    public function get_icon()
    {
        return "eicon-product-images";
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
            \Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'exclude' => ['custom'],
                'include' => [],
                'default' => 'medium',
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Container width', 'miga_wc_elements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'miga_wc_elements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .miga_wc_elements_image',
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
        if (!isset($product) || empty($product)) {
            echo "<b> - please select a product - </b>";
            return;
        }

        $imgid = $product->get_image_id();
        $img = wp_get_attachment_image_src($imgid, $settings["thumbnail_size"]);
        if ($img) {
            echo '<figure class="miga_wc_elements_image"><img alt="image" src="' . esc_url($img[0]) . '" width="'.esc_attr($img[1]).'" height="'.esc_attr($img[2]).'"></figure>';
        }
    }

    protected function _content_template()
    {
    }
}
