<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'title',
        'no_of_customers',
        'start_date',
        'end_date',
        'user_id',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getStatusAttribute($value)
    {
        if ($value == 1) {
            return "Active";
        } else {
            return "Not Active";
        }
    }
}
