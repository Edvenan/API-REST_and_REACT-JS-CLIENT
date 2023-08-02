<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * add the One-To-One relationship between User and Role Model.
     */
    public function role()
    {
        return $this->hasOne(Role::class);
    }

    /**
     * Method to list all the player's games
     * ordered by date
     */
    // 
    public function games(){
        return Game::where('user_id', $this->id)->orderBy('created_at', 'asc')->get();
    }

    /**
     * Method to get the player's wins rate
     */
    // 
    public function winsRate(){
        if ($this->games()->count()) {
        return floatval($this->gameS()->where('result', 1)->count() / $this->games()->count());
        }
        return 0;
    }
}
