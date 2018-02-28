<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\SlidersRepository;
use Config;
use Illuminate\Http\Request;

class IndexController extends SiteController
{
    public function __construct(SlidersRepository $slidersRepository, PortfolioRepository $portfolioRepository, ArticlesRepository $articlesRepository)
    {
        //передаем в родительский контроллер репо с меню
        parent::__construct(new MenuRepository(new Menu()));

        //получаем репозиторий для работы с моделью Slider
        $this->s_rep = $slidersRepository;
        $this->p_rep = $portfolioRepository;
        $this->a_rep = $articlesRepository;

        //указываем главный шаблон
        $this->template =  env('THEME').'.index';
        $this->bar = 'right';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Работаем с right bar Остальное в методе рендерАутпут в родительском контроллере
        $articles = $this->getArticles();

        $this->contentRightBar = view(env('THEME').'.indexBar')->with('articles', $articles)->render();

        //получаем портфолио и рендерим и передаем $content
        $portfolio = $this->getPortfolio();

        $content = view(env('THEME').'.content')->with('portfolios', $portfolio)->render();
        $this->vars = array_add($this->vars,'content', $content);


        //получаем коллекцию данных слайдера
        //рендерим шаблон с данными
        //передаем его для использования в index.blade
        $sliderItems = $this->getSliders();
        $sliders = view(env('THEME').'.slider')->with('sliders', $sliderItems)->render();
        $this->vars = array_add($this->vars,'sliders', $sliders);

        //переопределяем метатеги
        $this->meta_desc = 'Home Page';
        $this->title = 'Home Page';
        $this->keywords = 'Home Page';


        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
     * получаем слайдер
     */
    private function getSliders()
    {
        $sliders = $this->s_rep->get();
        if($sliders->isEmpty()) {
            return false;
        }

        //функция для замены нужного поля каждого элемента коллекции
        $sliders->transform(function($item, $key){
            $item->img = Config::get('settings.slider_path').'/'.$item->img;
            return $item;
        });

        return $sliders;
    }

    private function getPortfolio()
    {
        $portfolio = $this->p_rep->get('*', Config::get('settings.home_port_count'));
        return $portfolio;
    }

    /**
     * @return mixed
     */
    private function getArticles()
    {
        $articles = $this->a_rep->get(['title', 'img', 'created_at', 'alias'], Config::get('settings.home_articles_count'));
        return $articles;
    }
}
