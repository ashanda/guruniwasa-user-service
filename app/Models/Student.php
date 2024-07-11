<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;

class Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
     
     protected $fillable = [
        'username',
        'password',
        'full_name',
        'student_code',
        'grade',
        'birthday',
        'gender',
        'address',
        'school',
        'district',
        'city',
        'parent_phone',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
