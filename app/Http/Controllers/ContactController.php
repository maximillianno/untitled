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

        //at first we handle the post method
        if ($request->isMethod('POST')){

            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'email' => 'Поле :attribute должно содержать правильный почтовый адрес'
            ];

//            dd($request->all());
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text'  => 'required'
            ], $messages);

            $data = $request->all();
            //TODO: uncomment mail sender
//            $result = \Mail::send(env('THEME').'.email', ['data' => $data], function ($m) use ($data){
//                $mailAdmin = env('mail_admin');
////                $m->from($data['email'], $data['name']);
//                $m->from($mailAdmin, $data['name']);
//                $m->to($mailAdmin, 'Mr. Admin')->subject($data['email']);
//            });


            return redirect()->route('contacts')->with('status', 'email is send');

//            //null always here
//            if($result){
////                return redirect()->route('contacts')->with('status', 'email is send');
//                return redirect()->route('contacts')->with('status', 'email is send');
//            }


        }



        $this->title = "Контакты";

        $this->contentLeftBar = view(env('THEME').'.contactBar')->render();

        //ее перенесли в рендерАутпут()
//        $this->vars = array_add($this->vars, 'leftBar', $this->contentLeftBar);

        $content = view(env('THEME').'.contactContent')->render();
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();

    }
}
