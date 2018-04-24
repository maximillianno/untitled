<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lavary\Menu\Menu;

class AdminController extends Controller
{
    //
    protected $p_rep;

    protected $a_rep;

    protected $user;

    protected $template;

    protected $content;

    protected $title;

    protected $vars;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        //Это не работает, потому что конструктор отрабатывает позже
//        $this->user = Auth::user();
//        dd($this->user);
//
//        if(!$this->user){
//            abort(403);
//        }
    }


    /**
     * Method
     */
    public function renderOutput()
    {
        $this->vars = array_add($this->vars, 'title', $this->title);

        //получаем меню
        $menu = $this->getMenu();

        //рендерим навигацию и добавляем в переменные
        try {
            $navigation = view(env('THEME') . '.admin.navigation')->with(['menu' => $menu])->render();
        } catch (\Throwable $e) {
        }
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        //рендерим контент если есть
        if ($this->content){
            $this->vars = array_add($this->vars, 'content', $this->content);
        }


        //рендерим футер и добавляем в переменные
        try {
            $footer = view(env('THEME') . '.admin.footer')->render();
        } catch (\Throwable $e) {
        }
        $this->vars = array_add($this->vars, 'footer', $footer);

        return view($this->template)->with($this->vars);



    }

    private function getMenu()
    {
        return (new \Lavary\Menu\Menu)->make('admin', function($menu)  {
            $menu->add('Статьи', ['route' => 'admin.articles.index']);
            $menu->add('Портфолио', ['route' => 'admin.articles.index']);
            $menu->add('Меню', ['route' => 'admin.articles.index']);
            $menu->add('Пользователи', ['route' => 'admin.articles.index']);
            $menu->add('Привелегии', ['route' => 'admin.articles.index']);
        });
    }


}