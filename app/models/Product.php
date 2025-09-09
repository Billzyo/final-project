<?php 


/**
 * products class
 */
class Product extends Model
{
	
	protected $table = "products";

	protected $allowed_columns = [

				'barcode',
				'user_id',
				'category_id',
				'description',
				'qty',
				'amount',
				'image',
				'date',
				'views',
			];


 	public function validate($data, $id = null)
	{
		$errors = [];

			//check description
			if(empty($data['description']))
			{
				$errors['description'] = "Product description is required";
			}else
			if(!preg_match('/[a-zA-Z0-9 _\-\&\(\)]+/', $data['description']))
			{
				$errors['description'] = "Only letters allowed in description";
			}

			//check category_id
			if(empty($data['category_id']))
			{
				$errors['category_id'] = "Product category is required";
			}else
			if(!preg_match('/^[0-9]+$/', $data['category_id']))
			{
				$errors['category_id'] = "Category must be a valid selection";
			}

			//check qty
			if(empty($data['qty']))
			{
				$errors['qty'] = "Product quantity is required";
			}else
			if(!preg_match('/^[0-9]+$/', $data['qty']))
			{
				$errors['qty'] = "quantity must be a number";
			}

			//check amount
			if(empty($data['amount']))
			{
				$errors['amount'] = "Product amount is required";
			}else
			if(!preg_match('/^[0-9.]+$/', $data['amount']))
			{
				$errors['amount'] = "amount must be a number";
			}

			//check image
			$max_size = 4;//mbs
			$size = $max_size * (1024 * 1024);

			if(!$id || ($id && !empty($data['image']))){

				if(empty($data['image']))
				{
					$errors['image'] = "Product image is required";
				}else
				if(!($data['image']['type'] == "image/jpeg" || $data['image']['type'] == "image/png"))
				{
					$errors['image'] = "Image must be a valid JPEG or PNG";
				}else
				if($data['image']['error'] > 0)
				{
					$errors['image'] = "The image failed to upload. Error No.".$data['image']['error'];
				}else
				if($data['image']['size'] > $size)
				{
					$errors['image'] = "The image size must be lower than ".$max_size."Mb";
				}
			}

			
		return $errors;
	}

	public function generate_barcode()
	{

		return "2222" . rand(1000,999999999);
	}

	public function generate_filename($ext = "jpg")
	{

		return hash("sha1",rand(1000,999999999))."_".rand(1000,9999).".".$ext;
	}

	public function getProductsWithCategories($limit = 10, $offset = 0, $order = "desc", $order_column = "views")
	{
		$query = "select p.*, c.name as category_name 
				  from $this->table p 
				  left join categories c on p.category_id = c.id 
				  order by p.$order_column $order 
				  limit $limit offset $offset";
		
		$db = new Database;
		return $db->query($query);
	}

	public function getProductsByCategory($category_id, $limit = 10, $offset = 0, $order = "desc", $order_column = "views")
	{
		$query = "select p.*, c.name as category_name 
				  from $this->table p 
				  left join categories c on p.category_id = c.id 
				  where p.category_id = :category_id 
				  order by p.$order_column $order 
				  limit $limit offset $offset";
		
		$db = new Database;
		return $db->query($query, ['category_id' => $category_id]);
	}

	public function searchProductsWithCategory($search_text, $category_id = null, $limit = 20)
	{
		$query = "select p.*, c.name as category_name 
				  from $this->table p 
				  left join categories c on p.category_id = c.id 
				  where (p.description like :search OR p.barcode = :barcode)";
		
		$params = [
			'search' => "%$search_text%",
			'barcode' => $search_text
		];

		if ($category_id && $category_id !== 'all') {
			$query .= " AND p.category_id = :category_id";
			$params['category_id'] = $category_id;
		}

		$query .= " order by p.views desc limit $limit";
		
		$db = new Database;
		return $db->query($query, $params);
	}

}