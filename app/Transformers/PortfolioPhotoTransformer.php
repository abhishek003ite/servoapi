<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:01 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\PortfolioPhoto;

class PortfolioPhotoTransformer extends TransformerAbstract
{
    public function transform(PortfolioPhoto $portfolioPhoto)
    {
        return [
            //
        ];
    }
}