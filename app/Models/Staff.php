<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Staff extends Model
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    protected $fillable = [
        'photo', 'name', 'gender', 'contact_no', 'second_contact_no', 'birthday', 'nic_no', 'address', 'basic', 'fixed_allowance', 'working_days_and_hours', 'email', 'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = ['deleted_at'];

    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
