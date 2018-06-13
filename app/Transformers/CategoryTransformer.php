<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
	
	public function transform(Category $category)
	{
		return [
			'id' => $category->id,
			'categoryName' => $category->cat_name,
			'categoryDescription' => $category->cat_desc,
			'categoryIcon' => $category->cat_icon,
		];
	}
}