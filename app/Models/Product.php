<?php

namespace App\Models;

use Exception;
use Illuminate\Http\Request;
use App\Filters\Product\ProductFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'barcode',
        'name',
        'description',
        'category_id',
        'base_price',
        'vat',
        'weight',
        'unit_id',
        'stock_id',
        'has_ingredients',
        'discount_id',
        'discounted_from_date',
        'discounted_until_date',
    ];

    public $with = ['unit', 'stock', 'category', 'ingredients'];

    protected $appends = array('price', 'quantity', 'finalDiscount');

    public function getFinalDiscountAttribute() 
    {
        if($this->discount != null) {
            return $this->discount;
        }

        if($this->category->discount != null) {
            return $this->category->discount;
        }
    }

    public function getPriceAttribute()
    {
        if($this->category->discount && $this->discount) {
            if($this->category->discount > $this->discount) {
                $finalPrice = $this->calculateDiscount($this->base_price, $this->category->discount->value);

            } else if ($this->category->discount == $this->discount) {
                $finalPrice = $this->calculateDiscount($this->base_price, $this->category->discount->value);

            } else if ($this->category->discount < $this->discount) {
                $finalPrice = $this->calculateDiscount($this->base_price, $this->discount->value); 
            }
        } else if($this->category->discount) {
            $finalPrice = $this->calculateDiscount($this->base_price, $this->category->discount->value);
        } else if($this->discount) {
            $finalPrice = $this->calculateDiscount($this->base_price, $this->discount->value); 
        } else {
            $finalPrice = $this->base_price;
        }

        $finalPrice += $finalPrice * ($this->category->vat / 100);

        return number_format($finalPrice, 2, '.', '');
    }

    public function getQuantityAttribute() 
    {
        if($this->has_ingredients) {

            $ingredients = $this->ingredients;

            $quantityArray = array();
            
            foreach($ingredients as $ingredient) {
                $howManyProductsCanBeMadeFromThisIngredient = floor($ingredient->stock->quantity / $ingredient->pivot->quantity);
                if($howManyProductsCanBeMadeFromThisIngredient === 0) {
                    $quantity = 0;
                    return $quantity;
                }else {
                    array_push($quantityArray, $howManyProductsCanBeMadeFromThisIngredient);
                }
            }

            $quantity = min($quantityArray);
        } else {
            $quantity = $this->stock->quantity;
        }

        return $quantity;
    }

    private function calculateDiscount($basePrice, $discount) 
    {
        return $basePrice - $basePrice * ($discount / 100); 
    }

   
    public function category() 
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function stock() 
    {
        return $this->belongsTo('App\Models\Stock');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function ingredients() 
    {
        return $this->belongsToMany('App\Models\Ingredient')->withPivot('quantity');
    }

    public function discount() 
    {
        return $this->belongsTo(Discount::class);
    }

    public function orders() 
    {
        return $this->belongsToMany(Order::class);
    }

    public function scopeFilter(Builder $builder, array $data)
    {
        return (new ProductFilter($data))->filter($builder);
    }

}
