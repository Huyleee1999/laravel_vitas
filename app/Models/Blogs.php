<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expert;
use App\Models\Professions;

class Blogs extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_blogs';

    protected $fillable = [
        'name',
        'description',
        'content',
        'profession_id',
        'expert_id',
        'is_highlight',
        'type',
        'status',
        'created_at',
        'updated_at'
    ];

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }

    public function experts() {
        return $this->belongsTo(Expert::class, 'expert_id', 'id');
    }
}
