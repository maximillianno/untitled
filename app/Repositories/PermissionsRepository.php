<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.05.2018
 * Time: 8:36
 */

namespace App\Repositories;


use App\Permission;

class PermissionsRepository extends Repository
{
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

}