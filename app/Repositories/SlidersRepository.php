<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Slider;

class SlidersRepository extends Repository
{

    public function __construct(Slider $slider)
    {
        $this->model = $slider;
    }


}