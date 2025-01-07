<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        "name",
        "code",
        "website",
        "service_contact",
        "status"
    ];
    use HasFactory;
}
