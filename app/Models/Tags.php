<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TagPrograms;

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

    public function tagprograms() {
        return $this->hasMany(Tagprograms::class, 'tag_id', 'id');
    }
}
