<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends AdminController
{
    //
    public function __construct()
    {
        parent::__construct();

//        if (\Gate::denies('VIEW_ADMIN')){
//            abort(403);
//        }
        $this->template = env('THEME').'.admin.index';

    }

    public function index()
    {
        //Хз когда это срабатывает, если этот контроллер с Auth middleware
//        if (!\Auth::user()){
//            abort(403);
//        }

        if (\Gate::denies('VIEW_ADMIN')){
            abort(403);
        }

        $this->title = 'Панель администратора';
        return $this->renderOutput();
    }
}
