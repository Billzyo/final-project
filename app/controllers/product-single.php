<?php

$id = $_GET['id'] ?? null;

if(!$id){
    die("Product ID is required");
}

$productClass = new Product();
$product = $productClass->first(['id' => $id]);

if(!$product){
    die("Product not found");
}

require views_path('products/product-single');
