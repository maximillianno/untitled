<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Article;

class ArticlesRepository extends Repository
{

    public function __construct(Article $article)
    {
        $this->model = $article;
    }
    public function one($alias, $attr = [])
    {
        $article = parent::one($alias, $attr);



        if ($article && $attr){
            $article->load('comments');
            $article->comments->load('user');
        }
        return $article;
    }


}