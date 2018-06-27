<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Filter;
use App\Http\Requests\MenusRequest;
use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends AdminController
{
    //Меню репозиторий
    /**
     * @var MenuRepository
     */
    protected $m_rep;

    public function __construct(MenuRepository $menusRepository, ArticlesRepository $articlesRepository, PortfolioRepository $portfolioRepository)
    {
        parent::__construct();
        $this->m_rep = $menusRepository;
        $this->p_rep = $portfolioRepository;
        $this->a_rep = $articlesRepository;

        $this->template = env('THEME').'.admin.menus';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('VIEW_ADMIN_MENU')){
            abort(403);
        }

        $menu = $this->getMenus();

        $this->content = view(env('THEME').'.admin.menus_content')->with('menus', $menu);

        return $this->renderOutput();



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Меню
        $this->title = 'Новый пункт меню';
        $menus = $this->getMenus()->roots();

        //Преобразовываем коллекцию в массив для select option
        $menus = $menus->reduce(function ($returnMenu, $menu){
            $returnMenu[$menu->id] = $menu->title;
            return $returnMenu;

        }, ['0' => 'Родительский пункт меню']);



        //Категории
        $categories = Category::select('title', 'alias', 'id', 'parent_id')->get();
        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел Блог');

        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }


        //Статьи
        $articles = $this->a_rep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce(function($returnArticles, $article){
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);


        //работа с фильтрами
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilter, $model){
            $returnFilter[$model->alias] = $model->title;
            return $returnFilter;
        },['parent' => 'Раздел портфолио']);


        //теперь работа с портфолио
        $portfolios = $this->p_rep->get(['id', 'alias', 'title']);
        $portfolios = $portfolios->reduce(function ($returnPortfolios, $model) {
            $returnPortfolios[$model->alias] = $model->title;
            return $returnPortfolios;
        },[]);


        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        //
        $result = $this->m_rep->addMenu($request);

        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
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
    public function edit(Menu $menu)
    {
        //
//        dd($menu);
        $this->title = 'Редактирование пункта меню';


        //Переменные для типа аккордеона и выделенной опции
        $type = false;
        $option = false;


//        dd(app('request')->create($menu->path));

        //TODO: валидация ссылки
        //\Route::getRoutes()
        $route = app('router')->getRoutes()->match(request()->create($menu->path));


        $aliasRoute = $route->getName();

        $parameters = $route->parameters();

//        dd($parameters);
        if ($aliasRoute == 'articles.index' || $aliasRoute == 'articlesCat'){
            $type = 'blogLink';
            $option = isset($parameters['cat_alias']) ? $parameters['cat_alias'] : 'parent';
        } elseif ($aliasRoute == 'articles.show'){
            $type = 'blogLink';
            $option =  isset($parameters['alias']) ? $parameters['alias'] : '';
        } elseif ($aliasRoute == 'portfolios.index'){
            $type = 'portfolioLink';
            $option = 'parent';
        } elseif ($aliasRoute == 'portfolios.show'){
            $type = 'portfolioLink';
            $option =  isset($parameters['alias']) ? $parameters['alias'] : '';
        } else {
            $type = 'customLink';
        }

        //Массив из пунктов главного меню сайта
        $menus = $this->getMenus()->roots();
        //Преобразовываем коллекцию в массив для select option
        $menus = $menus->reduce(function ($returnMenu, $menu){
            $returnMenu[$menu->id] = $menu->title;
            return $returnMenu;

        }, ['0' => 'Родительский пункт меню']);



        //Категории $list - многомерный массив с категориями  0 => "Не используется"
        //  "parent" => "Раздел Блог"
        $categories = Category::select('title', 'alias', 'id', 'parent_id')->get();
        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел Блог');

        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
//        dd($list);



        //Статьи - массив списка статей вида "article-3" => "Section shortcodes & sticky posts!"
        $articles = $this->a_rep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce(function($returnArticles, $article){
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);
//        dd($articles);


        //работа с фильтрами
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilter, $model){
            $returnFilter[$model->alias] = $model->title;
            return $returnFilter;
        },['parent' => 'Раздел портфолио']);



        //теперь работа с портфолио
        $portfolios = $this->p_rep->get(['id', 'alias', 'title']);
        $portfolios = $portfolios->reduce(function ($returnPortfolios, $model) {
            $returnPortfolios[$model->alias] = $model->title;
            return $returnPortfolios;
        },[]);


        $this->content = view(env('THEME').'.admin.menus_create_content')
            ->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios, 'type' => $type, 'option' => $option, 'menu' => $menu]);
        return $this->renderOutput();

        dd($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenusRequest $request, Menu $menu)
    {
        //
        $result = $this->m_rep->updateMenu($request, $menu);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
        $result = $this->m_rep->deleteMenu($menu);
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * @return bool|\Lavary\Menu\Menu
     */
    private function getMenus()
    {
        $menu = $this->m_rep->get();
        if ($menu->isEmpty()){
            return false;
        }

        return (new \Lavary\Menu\Menu)->make('forMenuPart', function ($m) use ($menu) {
            foreach ($menu as $item) {

                if ($item->parent == 0){
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)){
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }
        });
    }
}
