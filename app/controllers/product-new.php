<?php


$errors = [];
$category = new Category();
$categories = $category->getAllCategories();

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $product = new Product();

    $_POST['date'] = date("Y-m-d H:i:s");
    $_POST['user_id'] = auth("id");
    $_POST['barcode'] = empty($_POST['barcode']) ? $product->generate_barcode() : $_POST['barcode'];

    // Only set image if file was uploaded
    if(!empty($_FILES['image']['name']))
    {
        $imageFile = $_FILES['image'];
        $folder = "uploads/";
        if(!file_exists($folder))
        {
            mkdir($folder,0777,true);
        }

        $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $destination = $folder . $product->generate_filename($ext);

        if(move_uploaded_file($imageFile['tmp_name'], $destination)){
            $_POST['image'] = $destination;
        } else {
            $errors['image'] = "Failed to upload image.";
        }
    }

    // Prepare data for validation, include image file array if uploaded
    $dataForValidation = $_POST;
    if(!empty($_FILES['image']['name'])){
        $dataForValidation['image'] = $_FILES['image'];
    }
    $errors = $product->validate($dataForValidation);
    if(empty($errors)){
        $product->insert($_POST);
        redirect('admin&tab=products');
    }
}

require views_path('products/product-new');