<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:02 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\SubCategory;

class SubCategoryTransformer extends TransformerAbstract
{
    public function transform(SubCategory $subCategory)
    {
        return [
            'id'            =>  $subCategory->id,
            'name'          =>  $subCategory->name,
            'category_id'   =>  $subCategory->category_id,
        ];
    }
}