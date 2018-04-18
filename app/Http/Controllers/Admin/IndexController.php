<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends AdminController
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->template = env('THEME').'.admin.index';

    }

    public function index()
    {
        if (!\Auth::user()){
            abort(403);
        }
        $this->title = 'Панель администратора';
        return $this->renderOutput();
    }
}
