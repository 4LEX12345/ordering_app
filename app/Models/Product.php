<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Flavor;
use App\Models\Brand;
use App\Models\ProductCategory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id','product_category_id','flavor_id'];

    public function flavor(){
        return $this->belongsTo(Flavor::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function productCategory(){
        return $this->belongsTo(ProductCategory::class);
    }
}
