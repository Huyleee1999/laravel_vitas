<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Professions;
use App\Models\Evaluates;
use App\Models\Bookings;

class Expert extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'phone',
        'profession_id',
        'company',
        'experience',
        'email',
        'password',
        'project',
        'type',
        'status',
        'description',
        'position',
        'rate',
        'price',
        'customer',
        'created_at',
        'updated_at',
    ];

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }

    public function evaluates() {
        return $this->hasMany(Evaluates::class, 'expert_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Bookings::class, 'expert_id', 'id');
    }

    public function blogs() {
        return $this-> hasMany(Blogs::class, 'expert_id', 'id');
    }
}
