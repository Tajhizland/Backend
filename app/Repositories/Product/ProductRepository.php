<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends  BaseRepository implements  ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByUrl($url)
    {
       return $this->get([["url",$url]],1);
    }
    public function incrementViewCount($product)
    {
        $product->increment('view');
    }
}
