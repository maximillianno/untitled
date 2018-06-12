<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.05.2018
 * Time: 8:36
 */

namespace App\Repositories;


use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class PermissionsRepository extends Repository
{
    //
    protected $rol_rep;

    public function __construct(Permission $permission, RolesRepository $rolesRepository)
    {
        $this->model = $permission;

        $this->rol_rep = $rolesRepository;
    }

    /**
     * @param Request $request
     */
    public function changePermissions($request)
    {
        //TODO Разобраться
        //Перенести Авторизацию в реквест,
        if (\Gate::denies('update', $this->model)){
            abort(403);
        }

        $data = $request->except('_token');

        $roles = $this->rol_rep->get();

        /** @var Role $role */
        foreach ($roles as $role) {
            //Если ИД текущей роли есть среди ключей массива из реквеста
            if (isset($data[$role->id])){
                //Сохраняем модель и передаем массив с правами
                $role->savePermissions($data[$role->id]);
            } else {
                $role->savePermissions([]);
            }
        }
        


        return ['status' => 'Права обновлены'];

    }

}