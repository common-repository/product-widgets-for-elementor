<?php

$products = [];

if (!class_exists('WooCommerce')) {
    return;
}

$args = array(
    'orderby'  => 'name',
);

$p = wc_get_products($args);
foreach ($p as $key => $product) {
    if (isset($product->id)) {
        $products[$product->get_id()] = $product->get_name();
    }
}

$this->add_control(
    'product_id',
    [
        'label' => "Product",
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $products,
    ]
);
