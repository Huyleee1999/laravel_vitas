<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tags;
use App\Models\FeaturePrograms;

class TagPrograms extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_tagprograms';

    protected $fillable = [
        'tag_id',
        'program_id',
        'created_at',
        'updated_at'
    ];

    public function tags() {
        return $this->belongsTo(Tags::class, 'tag_id', 'id');
    }

    public function programs() {
        return $this->belongsTo(FeaturePrograms::class, 'program_id', 'id');
    }
}
