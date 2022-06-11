<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatDetail extends Model
{
    protected $fillable = [
        'user_send_1',
        'user_send_2',
        'content',
        'room_id'
    ];
}
