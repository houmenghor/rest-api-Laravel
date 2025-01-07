<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        "firstname",
        "lastname",
        "gender",
        "tel",
        "email",
        "dob"
    ];
    use HasFactory;
}
