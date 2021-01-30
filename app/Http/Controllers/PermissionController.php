<?php

namespace App\Http\Controllers;

use App\Exports\PermissionsExport;
use App\Imports\PermissionsImport;
use App\Traits\ExcelTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    use ExcelTrait;

    /**
     * Get default view / data
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Permission::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.user-managements.permissions', [
                'title' => 'Permissions',
            ]);
        }

        return DataTables::of(Permission::query())
                            ->make(true);
    }

    /**
    * Export Permissions data.
    *
    * @param   Request  $request
    *
    * @return  mixed
    */
    public function export(Request $request)
    {
        $this->authorize('export', Permission::class);

        $allowedFormats = ['xlsx', 'csv'];

        if ($request->get('export') != 1) {
            return view('components.export-modal', [
                'action' => route('administrator.user-managements.permissions.export'),
                'formats' => $allowedFormats,
            ]);
        }

        return $this->exportFile($request, new PermissionsExport($request->take), 'permissions', $allowedFormats);
    }

    /**
     * Import Permissions data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function import(Request $request)
    {
        $this->authorize('export', Permission::class);

        if ($request->method() === 'GET') {
            return view('components.import-modal', [
                'action' => route('administrator.user-managements.permissions.import'),
                'dataTable' => '#permissions-datatable',
            ]);
        }

        $allowedFormats = ['xlsx', 'csv'];

        return $this->importFile($request, new PermissionsImport, $allowedFormats);
    }
}
