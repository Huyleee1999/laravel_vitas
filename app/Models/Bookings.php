<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expert;
use App\Models\Users;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_bookings';

    protected $fillable = [
        'name',
        'date',
        'time',
        'phone',
        'content',
        'link',
        'expert_id',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function experts() {
        return $this->belongsTo(Expert::class, 'expert_id', 'id');
    }

    public function users() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
