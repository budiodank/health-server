<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'telephone',
        'street',
        'village',
        'city',
        'province',
        'country',
        'weight',
        'height',
        'disease',
        'snore',
        'access',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCode($lenght)
    {
        $char= '0123456789abcdefABCDEF';
        $string = '';
        for ($i = 0; $i < $lenght; $i++) {
          $pos = rand(0, strlen($char)-1);
          $string .= $char[$pos];
        }
        return $string;
    }

    public function getNumber($lenght)
    {
        $char= '123456789';
        $string = '';
        for ($i = 0; $i < $lenght; $i++) {
          $pos = rand(0, strlen($char)-1);
          $string .= $char[$pos];
        }
        return $string;
    }
}
