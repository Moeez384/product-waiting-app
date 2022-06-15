<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'success_message',
        'email_already_exist_message',
        'does_not_have_account_message',
        'product_already_in_the_waiting_message',
    ];
}
