<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainCheck extends Model
{
    use HasFactory;
    protected $fillable = [
        'updated_at',
        'created_at',
    ];
}
