<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_id',
        'type',
        'product_or_collection_id',
        'title',
    ];

    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id', 'id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_categories');
    }
}
