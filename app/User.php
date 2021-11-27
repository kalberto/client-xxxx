<?php

/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 30/10/2017
 * Time: 18:20
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/10/2017
 * Time: 18:20
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
