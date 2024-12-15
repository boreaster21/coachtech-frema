<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['method_name', 'description'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
