<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Portfolio;

class PortfolioRepository extends Repository
{

    public function __construct(Portfolio $portfolio)
    {
        $this->model = $portfolio;
    }

    //переопределенный метод для работы с картинками, чтобы не делать это в контроллере
    public function one($alias, $attr = [])
    {
        $portfolio = parent::one($alias, $attr);
        if ($portfolio->img){
            $portfolio->img = json_decode($portfolio->img);
        }
        return $portfolio;
    }


}