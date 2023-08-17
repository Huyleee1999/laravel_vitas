<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_tags';

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',

    ];

}
