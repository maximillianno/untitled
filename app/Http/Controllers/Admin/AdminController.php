<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lavary\Menu\Menu;

class AdminController extends Controller
{
    //портфолио репозиторий
    protected $p_rep;

    //артиклс репозиторий
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
     * @throws \Throwable
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
            $navigation = view(env('THEME') . '.admin.navigation')->with(['menu' => $menu])->render();
        }
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        //рендерим контент если есть
        if (isset($this->content)){
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
            $menu->add('Меню', ['route' => 'admin.menus.index']);
            $menu->add('Пользователи', ['route' => 'admin.articles.index']);
            $menu->add('Привелегии', ['route' => 'admin.permissions.index']);
        });
    }


}
