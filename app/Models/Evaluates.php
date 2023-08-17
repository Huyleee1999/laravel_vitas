<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
use App\Models\Expert;

class Evaluates extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_evaluates';

    protected $fillable = [
        'rate',
        'expert_id',
        'user_id',
        'status',
        'content',
        'created_at',
        'updated_at',
    ];

    public function users() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function experts() {
        return $this->belongsTo(Expert::class, 'expert_id', 'id');
    }
}
