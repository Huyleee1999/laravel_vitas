<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'created_at',
        'updated_at',
    ];

    public function professions() {
        return $this->belongsTo(Professions::class, 'profession_id', 'id');
    }

    public function evaluates() {
        return $this->hasMany(Evaluates::class, 'expert_id', 'id');
    }
}
