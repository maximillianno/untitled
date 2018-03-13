<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Comment;

class CommentsRepository extends Repository
{

    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }


}