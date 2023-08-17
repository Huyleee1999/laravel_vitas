<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Professions;

class Questions extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_questions';

    protected $fillable = [
        'profession_id',
        'content',
        'status',
        'answer',
        'created_at',
        'updated_at'
    ];

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }
}
