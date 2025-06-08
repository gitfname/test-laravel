<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $fillable = ["username", "email", "phone_number", "is_phone_verified", "password"];
}
