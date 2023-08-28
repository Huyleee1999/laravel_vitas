<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Professions;
use App\Models\Evaluates;
use App\Models\Bookings;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'phone',
        'profession_id',
        'city_id',
        'email',
        'password',
        'company',
        'experience',
        'project',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }

    public function evaluates() {
        return $this->hasMany(Evaluates::class, 'user_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Bookings::class, 'user_id', 'id');
    }
}
