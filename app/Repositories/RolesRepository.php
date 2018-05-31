<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.05.2018
 * Time: 8:37
 */

namespace App\Repositories;


use App\Role;

class RolesRepository extends Repository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

}