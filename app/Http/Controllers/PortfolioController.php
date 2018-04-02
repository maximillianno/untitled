<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function __construct(PortfolioRepository $portfolioRepository)
    {
        //передаем в родительский контроллер репо с меню
        parent::__construct(new MenuRepository(new Menu()));


        $this->p_rep = $portfolioRepository;

        //указываем главный шаблон
        $this->template =  env('THEME').'.portfolios';

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио';
        $this->meta_desc = 'Портфолио';


        $portfolios = $this->getPortfolios();
//        dd($portfolios);



        //рендерим контент и передаем в макет
        $content = view(env('THEME').'.portfoliosContent')->with('portfolios', $portfolios)->render();
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

    private function getPortfolios()
    {
        $portfolios = $this->p_rep->get(['*'], false, true);
        if ($portfolios) {
            $portfolios->load('filter');
        }
        return $portfolios;

    }
}
