<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'username', 'password', 'role_id', 'isaktif', 'api_token', 'AksesWeb', 'AksesApi', 'Koperasi'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function koperasi()
    {
        return $this->belongsTo('App\Koperasi', 'Koperasi');
    }

    public function nasabah()
    {
        return $this->hasOne('App\Nasabah');
    }

    public function kwitansis()
    {
        return $this->hasMany('App\Kwitansi');
    }

    public function kolektor()
    {
        return $this->hasOne('App\Kolektor');
    }

    public function isSuper()
    {
        if ($this->role_id === 0) {
            return true;
        }

        return false;
    }

    public function hasRole($role)
    {
        if ($this->isSuper()) {
            return true;
        }

        if (is_string($role)) {
            return $this->role->name === $role;
        }
        return $role->contains($this->role);
    }

    public function permissions()
    {
        return $this->hasManyThrough('App\Permission', 'App\Role');
    }
}
