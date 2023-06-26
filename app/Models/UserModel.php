<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class UserModel extends Model
{
    use HasFactory;
    protected $table = "m_users";
    protected $fillable = [
        'name_user', 
        'username',
        'password', 
        'profile_picture',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
