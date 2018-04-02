<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Lavary\Menu\Menu;

class SiteController extends Controller
{
    //portfolio
    protected $p_rep;
    //slider
    protected $s_rep;
    //articles
    protected $a_rep;
    //menu
    protected $m_rep;

    protected $template;


    /**
     * @var array
     * отрендеренные шаблоны секций
     */
    protected $vars = [];

    /**
     * @var string $bar
     */
    protected $bar = 'no';
    /**
     * @var string
     * Если она есть, то обрабатывается в renderOutput()
     */
    protected $contentLeftBar;
    protected $contentRightBar;

    //метатеги
    protected $title;
    protected $keywords;
    protected $meta_desc;


    public function __construct(MenuRepository $menuRepository)
    {

        $this->m_rep = $menuRepository;

    }


    /**
     * @return $this
     * @throws \Throwable
     */
    protected function renderOutput(){

        $menu = $this->getMenu();
//        dd($menu);


        /**
         * отрендеренная секция навигации. В корневом, потому что используется везде
         */
        $navigation = view(env('THEME').'.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        //contentRightBar уже отрендерели в IndexController@index
        if ($this->contentRightBar) {
            $rightBar = view(env('THEME').'.rightBar')->with('contentRightBar', $this->contentRightBar)->render();
            $this->vars = array_add($this->vars, 'rightBar', $rightBar);
        }

        //contentLeftBar - в контактах
        if ($this->contentLeftBar) {
            $leftBar = view(env('THEME').'.leftBar')->with('contentLeftBar', $this->contentLeftBar)->render();
            $this->vars = array_add($this->vars, 'leftBar', $leftBar);
        }

        //правый бар или левый
        $this->vars = array_add($this->vars, 'bar', $this->bar);

        //переменные метатегов
        $this->vars = array_add($this->vars, 'title', $this->title);
        $this->vars = array_add($this->vars, 'keywords', $this->keywords);
        $this->vars = array_add($this->vars, 'meta_desc', $this->meta_desc);



        $footer = view(env('THEME').'.footer');
        $this->vars = array_add($this->vars, 'footer', $footer);


        //возвращаем шаблон с переменными
        return view($this->template)->with($this->vars);
    }

    /**
     * @return collection of models
     */
    private function getMenu()
    {
        $menu = $this->m_rep->get();
//        dd($menu);
        $lavMenu = new Menu();
        $mBuilder = $lavMenu->make('MyNav', function ($m) use ($menu){
            foreach ($menu as $item) {
                //Если родительский пункт меню
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                //Если не родительский
                } else {
                    //Если есть родитель - он возвращается
                    if ($m->find($item->parent)){
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);

                    }
                }
            }
        });
//        dd($mBuilder);


        return $mBuilder;
    }


}
