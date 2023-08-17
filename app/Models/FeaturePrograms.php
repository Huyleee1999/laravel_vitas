<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturePrograms extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_featureprograms';

    protected $fillable = [
        'name',
        'description',
        'content',
        'status',
        'profession_id',
        'is_highlight',
        'created_at',
        'updated_at',
    ];

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }

    // public function tagprograms() {
    //     return $this->hasMany(Tagprograms::class, 'program_id', 'id');
    // }
}
