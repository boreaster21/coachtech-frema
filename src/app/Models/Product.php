<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'description', 
        'price', 
        'product_photo_path',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories'); 
    }

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'products_conditions'); 
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    public function purchasers()
    {
        return $this->belongsToMany(User::class, 'purchases')
        ->withPivot('purchased_at', 'status')
        ->withTimestamps();
    }
    public function getIsPurchasedAttribute()
    {
        return $this->purchasers()->exists();
    }

}
