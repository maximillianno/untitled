<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 12:00
 */

namespace App\Repositories;

use Config;



abstract class Repository
{
    /**
     * @var model
     */
    protected $model = false;

    /**
     * @return mixed
     */
    public function get()
    {
        $builder = $this->model->select('*');
        return $builder->get();
    }


}