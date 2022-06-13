<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'enable_app',
        'waiting_list_button_text',
        'waiting_list_button_text_color',
        'waiting_list_button_bg_color',
        'admin_email',
    ];
}
