<?php 

$errors = [];

$id = $_GET['id'] ?? null;
$product = new Product();
$category = new Category();

$row = $product->first(['id'=>$id]);
$categories = $category->getAllCategories();

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{

	$_POST['barcode'] = empty($_POST['barcode']) ? $product->generate_barcode():$_POST['barcode'];
	
	if(!empty($_FILES['image']['name']))
	{
		$_POST['image'] = $_FILES['image'];
	}

	$errors = $product->validate($_POST,$row['id']);
	if(empty($errors)){
		
		$folder = "uploads/";
		if(!file_exists($folder))
		{
			mkdir($folder,0777,true);
		}

		if(!empty($_POST['image']))
		{

			$ext = strtolower(pathinfo($_POST['image']['name'],PATHINFO_EXTENSION));

			$destination = $folder . $product->generate_filename($ext);
			move_uploaded_file($_POST['image']['tmp_name'], $destination);
			$_POST['image'] = $destination;

			//delete old image
			if(file_exists($row['image']))
			{
				unlink($row['image']);
			}
		
		}

		// Merge existing data with new data to avoid overwriting with empty values
		$update_data = array_merge($row, $_POST);

		$product->update($row['id'], $update_data);

		redirect('pg=admin&tab=products');
	}


}


require views_path('products/product-edit');

