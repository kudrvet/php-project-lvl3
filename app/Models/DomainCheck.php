<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'status_code',
        'keywords',
        'description',
        'updated_at',
        'created_at',
    ];
}
