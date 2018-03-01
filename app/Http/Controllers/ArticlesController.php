<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;

class ArticlesController extends SiteController
{
    public function __construct(PortfolioRepository $portfolioRepository, ArticlesRepository $articlesRepository)
    {
        //передаем в родительский контроллер репо с меню
        parent::__construct(new MenuRepository(new Menu()));


        $this->p_rep = $portfolioRepository;
        $this->a_rep = $articlesRepository;

        //указываем главный шаблон
        $this->template =  env('THEME').'.articles';
        $this->bar = 'right';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //получаем статьи с пагинацией
        $articles = $this->getArticles();

        //рендерим контент и передаем в макет
        $content = view(env('THEME').'.articles_content')->with('articles', $articles)->render();
        $this->vars = array_add($this->vars,'content', $content);

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

    private function getArticles($alias = false)
    {
        $articles = $this->a_rep->get(['id', 'title', 'alias', 'created_at', 'img', 'desc', 'user_id', 'category_id'], false, true );

        //для того, чтобы не плодить запросы
//        if ($articles) {
//            $articles->load('user', 'category', 'comments');
//        }

        return $articles;

    }
}
