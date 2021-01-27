<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Get default view / data
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            return view('pages.administrator.user-managements.permissions', [
                'title' => 'Permissions',
            ]);
        }

        return DataTables::of(Permission::query())
                            ->make(true);
    }
}
