<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $permission->roles = $permission->roles->pluck('name');
        }
        $success['permissions'] = $permissions;
        return $this->sendResponse($success, 'Permissions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:permissions,name',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $permission = Permission::create(['name' => $input['name']]);
        $success['permission'] = $permission;
        return $this->sendResponse($success, 'Permission created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::find($id);

        $permission->roles = $permission->roles->pluck('name');
        $success['permission'] = $permission;
        return $this->sendResponse($success, 'Permission retrieved successfully.');
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:permissions,name',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $permission = Permission::find($id);

        $permission->name = $input['name'];
        $permission->save();
        $success['permission'] = $permission;
        return $this->sendResponse($success, 'Permission updated successfully.', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        $permission->delete();
        $success['permission'] = $permission;
        return $this->sendResponse($success, 'Permission deleted successfully.');
    }
}
