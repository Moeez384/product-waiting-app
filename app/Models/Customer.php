<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'status',
        'cid',
        'user_id',
    ];
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'customer_categories');
    }
}
