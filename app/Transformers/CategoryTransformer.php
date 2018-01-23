<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 2:23 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;

class CategoryTransformer extends TransformerAbstract
{
    //Return Transform

    public function transform(Category $category)
    {
        return [
            'id'        =>  $category->id,
            'name'      =>  $category->name,
        ];
    }
}