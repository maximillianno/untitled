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
    public function get($select = '*', $take = false)
    {
        $builder = $this->model->select($select);
        if ($take) {
            $builder->take($take);
        }
        return $builder->get();
    }


}