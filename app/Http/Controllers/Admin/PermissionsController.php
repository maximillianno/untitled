<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends AdminController
{
    protected $per_rep;

    protected $rol_rep;

    /**
     * PermissionsController constructor.
     * @param PermissionsRepository $permissionsRepository
     * @param RolesRepository $rolesRepository
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __construct(PermissionsRepository $permissionsRepository, RolesRepository $rolesRepository)
    {
        parent::__construct();
        $this->per_rep = $permissionsRepository;
        $this->rol_rep = $rolesRepository;

        $this->template = env('THEME').'.admin.permissions';


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function index()
    {
        //Авторизация
        $this->authorize('EDIT_USERS');

        $this->title = 'Менеджер прав пользователей';

        //Промежуточные, хз нахуй нужны
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();

        //TODO исправить catch
        try {
            $this->content = view(env('THEME') . '.admin.permissions_content')->with(['roles' => $roles, 'priv' => $permissions])->render();
        } catch (\Throwable $e) {
            $this->content = view(env('THEME') . '.admin.permissions_content')->with(['roles' => $roles, 'priv' => $permissions])->render();
        }

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

        $result = $this->per_rep->changePermissions($request);

//        if (is_array($result) && !empty($result['error'])){
//            return back()->with($result);
//        }
        return back()->with($result);
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

    /**
     * @return mixed
     */
    private function getRoles()
    {
        $roles = $this->rol_rep->get();
        return $roles;
    }

    private function getPermissions()
    {
        $permissons = $this->per_rep->get();
        return $permissons;
    }
}
