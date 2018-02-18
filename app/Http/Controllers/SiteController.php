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
     * @var bool $bar
     */
    protected $bar = false;
    /**
     * @var string
     */
    protected $contentLeftBar;
    protected $contentRightBar;

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
         * отрендеренная секция навигации
         */
        $navigation = view(env('THEME').'.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        return view($this->template)->with($this->vars);
    }

    /**
     * @return collection of models
     */
    private function getMenu()
    {
        $menu = $this->m_rep->get();
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
