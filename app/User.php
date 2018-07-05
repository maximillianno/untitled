<?php

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
        'name', 'email', 'password', 'login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles(){
//        return $this->hasMany('App\Article', 'user_id', 'id' );
        return $this->hasMany('App\Article');
    }

    public function roles(){
        return $this->belongsToMany('App\Role', 'role_user');
    }

    /**
     * @param $permission
     * @param bool $require
     * @return bool
     */
    public function canDo($permission, $require = false){
        if (is_array($permission)){
//            dd($permission, $require);
            foreach ($permission as $item) {
                $permName = $this->canDo($item);
                if ($permName && !$require){
                    return true;
                } elseif (!$permName && $require){
                    return false;
                }
            }
            return $require;

        } else {
            foreach ($this->roles as $role){
                foreach ($role->permissions as $perm){
                    if (str_is($perm->name, $permission)){
//                    if ($perm->name == $permission){
                        return true;
                    }
                }
            }
        }


    }

    /**
     * @param $role
     * @param $require
     * @return bool
     */
    public function hasRole($role, $require = false){
        if (is_array($role)){
            foreach ($role as $item){
                $roleExist = $this->hasRole($item);
                if ($roleExist && !$require){
                    return true;
                } elseif (!$roleExist && $require){
                    return false;
                }
            }
            return $require;

        } else {
            foreach ($this->roles as $roleItem){
                if ($roleItem == $role){
                    return true;
                } else {
                    return false;
                }
            }
        }

    }
}
