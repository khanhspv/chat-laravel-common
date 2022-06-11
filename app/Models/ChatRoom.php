<?php

namespace App\Models;

use App\Models\Models\ChatDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'seed',
    ];

}
