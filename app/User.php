<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
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
    public function bill()
    {
        return $this->hasMany("App\Bills", "id_user", "id_user");
    }
    public function bill_state_order()
    {
        return $this->hasMany("App\Bills_state", "id_user_order", "id");
    }
    public function bill_state_confirm()
    {
        return $this->hasMany("App\Bills_state", "id_user_confirm", "id");
    }
    public function bill_state_transport()
    {
        return $this->hasMany("App\Bills_state", "id_user_transport", "id");
    }

    //////////role
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    protected $table = "users";
}
