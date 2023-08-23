<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
use App\Models\ExpertRegister;
use App\Models\Questions;
use App\Models\Expert;

class Professions extends Model
{
    use HasFactory;

    protected $table = 'vitas_blog_professions';

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',

    ];

    public function users() {
        return $this->hasOne(Users::class);
    }

    public function expertRegister() {
        return $this->hasOne(ExpertRegister::class);
    }
    
    public function programs() {
        return $this-> hasMany(Featureprograms::class, 'profession_id', 'id');
    }

    public function questions() {
        return $this-> hasMany(Questions::class, 'profession_id', 'id');
    }

    public function experts() {
        return $this-> hasMany(Expert::class, 'profession_id', 'id');
    }

    public function blogs() {
        return $this-> hasMany(Blogs::class, 'profession_id', 'id');
    }
}
