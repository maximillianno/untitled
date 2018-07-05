<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends AdminController
{
    protected $us_rep;
    protected $rol_rep;

    /**
     * UsersController constructor.
     * @param $us_rep
     * @param $rol_rep
     */
    public function __construct(UsersRepository $us_rep, RolesRepository $rol_rep)
    {
        parent::__construct();


        $this->us_rep = $us_rep;
        $this->rol_rep = $rol_rep;

        //он рендерится в АдминКонтроллере
        $this->template = env('THEME').'.admin.users';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function index()
    {
        //
        if (\Gate::denies('EDIT_USERS')){
            abort(403);
        }

        $this->title = 'Пользователи';
        $users = $this->us_rep->get();
        $this->content = view(env('THEME').'.admin.users_content')->with('users', $users)->render();

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
        $this->title = 'Новый пользователь';

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role){
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);

        $this->content = view(env('THEME').'.admin.users_create_content')->with('roles', $roles)->render();
        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        $result = $this->us_rep->addUser($request);
        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('admin')->with($result);
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
    public function edit(User $user)
    {
        //

        $this->title = 'Редактирование пользователя';

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role){
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);


        $this->content = view(env('THEME').'.admin.users_create_content')->with(['roles' => $roles, 'user' => $user])->render();
        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        //
        $result = $this->us_rep->updateUser($request, $user);
        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $result = $this->us_rep->deleteUser($user);
        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }
        return redirect('admin')->with($result);
    }

    private function getRoles()
    {
        return Role::all();
    }
}
