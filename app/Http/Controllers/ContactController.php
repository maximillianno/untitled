<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

class ContactController extends SiteController
{
    //
    public function __construct()
    {
        //передаем в родительский контроллер репо с меню
        parent::__construct(new MenuRepository(new Menu()));

        //указываем главный шаблон
        $this->template =  env('THEME').'.contacts';
        $this->bar = 'left';
    }

    public function index(Request $request){
        $this->title = "Контакты";

        $this->contentLeftBar = view(env('THEME').'.contactBar')->render();

        //ее перенесли в рендерАутпут()
//        $this->vars = array_add($this->vars, 'leftBar', $this->contentLeftBar);

        $content = view(env('THEME').'.contactContent')->render();
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();

    }
}
