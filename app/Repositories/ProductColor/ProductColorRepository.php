<?php

namespace App\Repositories\ProductColor;

use App\Models\ProductColor;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProductColorRepository extends  BaseRepository implements  ProductColorRepositoryInterface
{
public function __construct(ProductColor $model)
{
    parent::__construct($model);
}
}
