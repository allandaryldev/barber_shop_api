<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens;
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'type',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Accessors and mutators
     */

    public function getNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getInitialsAttribute()
    {
        $initials = collect([]);
        $words = collect(explode(' ', $this->firstname));

        $words->every(function ($word) use (&$initials) {
            return $initials->push($word[0]);
        });

        $initials->push($this->lastname[0]);

        return implode('', $initials->toArray());
    }

    /**
     * Affordances
     */

    public function activate ()
    {
        $this->update([
            'status' => 'active'
        ]);
    }

    public function deactivate()
    {
        $this->update([
            'status' => 'deactivated'
        ]);
    }

}
