<?php

namespace App\Http\Controllers;
use App\Models\RoleActivity;
use Illuminate\Http\Request;
use App\Traits\ResponseTraits;
use App\Services\RoleActivityServices;
use App\Http\Requests\StoreRoleActivityRequest;

class RoleActivityController extends Controller
{
    use ResponseTraits;

    public function __construct()
    {
        $this->model = new RoleActivity();
        $this->service = new RoleActivityServices();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $role_id = $request['id'];
        $result = $this->successResponse('Activities loaded successfully!');
        try
        {
            $result["data"] =  $this->service->load($role_id);
        } catch (\Throwable $th)
        {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRoleActivityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleActivityRequest $request)
    {
        $result = $this->successResponse('Activity created successfully!');
        try {
            $request['created_by'] = auth()->user()->id;
            RoleActivity::create($request->all());
        } catch (\Throwable $th)
        {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoleActivity  $role_activity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->successResponse('Activity retrieved successfully!');
        try {
            $result["data"] = $this->model::findOrfail($id);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoleActivity  $role_activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Models\RoleActivity  $role_activity
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRoleActivityRequest $request, $id)
    {
        $result = $this->successResponse('Activity updated successfully!');
        try {
            $this->model->findOrfail($id)->update($request->all());

        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoleActivity  $role_activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = RoleActivity::findOrfail($id);
        $result = $this->successResponse('Activity deleted successfully!');
        try {
            $role->delete();
        } catch (\Throwable $th)
        {
            return $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
