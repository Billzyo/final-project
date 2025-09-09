<?php 

/**
 * categories class
 */
class Category extends Model
{
	
	protected $table = "categories";

	protected $allowed_columns = [
		'name',
		'description',
		'created_at',
	];

	public function validate($data, $id = null)
	{
		$errors = [];

		//check name
		if(empty($data['name']))
		{
			$errors['name'] = "Category name is required";
		}else
		if(!preg_match('/[a-zA-Z0-9 _\-\&\(\)]+/', $data['name']))
		{
			$errors['name'] = "Only letters, numbers, spaces, and basic punctuation allowed in category name";
		}

		//check if name already exists (for new categories)
		if(!$id)
		{
			$query = "select id from $this->table where name = :name limit 1";
			$check = $this->query($query, ['name' => $data['name']]);
			if($check)
			{
				$errors['name'] = "Category name already exists";
			}
		}

		return $errors;
	}

	public function getAllCategories()
	{
		$query = "select * from $this->table order by name asc";
		return $this->query($query);
	}

	public function getCategoryById($id)
	{
		$query = "select * from $this->table where id = :id limit 1";
		$result = $this->query($query, ['id' => $id]);
		return $result ? $result[0] : false;
	}

	public function getCategoryByName($name)
	{
		$query = "select * from $this->table where name = :name limit 1";
		$result = $this->query($query, ['name' => $name]);
		return $result ? $result[0] : false;
	}

	public function getProductsByCategory($category_id)
	{
		$query = "select * from products where category_id = :category_id order by description asc";
		return $this->query($query, ['category_id' => $category_id]);
	}

	public function getCategoryStats()
	{
		$query = "select c.id, c.name, count(p.id) as product_count 
				  from categories c 
				  left join products p on c.id = p.category_id 
				  group by c.id, c.name 
				  order by c.name asc";
		return $this->query($query);
	}

}
