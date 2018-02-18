<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Menu;

class MenuRepository extends Repository
{

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }


}