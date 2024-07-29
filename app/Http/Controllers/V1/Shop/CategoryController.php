<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->get("filter");
        $pf = Product::query();
        foreach ($filters as $key => $value) {
            $pf->whereHas("productFilters", function ($q) use ($key, $value) {
                $q->where("filter_id", $key)->whereIn("filter_item_id", $value);
            });
        }
        $products= $pf->paginate();

        $cat = Category::find(1);

        return new CategoryResource($cat->setRelation("products",$products));
    }
}
