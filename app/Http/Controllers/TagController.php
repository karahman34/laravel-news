<?php

namespace App\Http\Controllers;

use App\Exports\TagsExport;
use App\Http\Requests\TagRequest;
use App\Imports\TagsImport;
use App\Models\Tag;
use App\Traits\ExcelTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    use ExcelTrait;

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.tags', [
                'title' => 'Tags'
            ]);
        }

        $auth = $request->user();

        return DataTables::of(Tag::query())
                            ->addColumn('actions', function (Tag $tag) use ($auth) {
                                $editButton = '';
                                $deleteButton = '';

                                if ($auth->can('update', $tag)) {
                                    $editButton = '<a href="'.route('administrator.tags.edit', ['tag' => $tag]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-tag-modal"><i class="fas fa-edit"></i></a>';
                                }
                                
                                if ($auth->can('delete', $tag)) {
                                    $deleteButton = '<a href="'.route('administrator.tags.destroy', ['tag' => $tag]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#tags-datatable" data-item-name="'.$tag->name.'"><i class="fas fa-trash"></i></a>';
                                }

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Export tags data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function export(Request $request)
    {
        $allowedFormats = ['xlsx', 'csv'];

        if ($request->get('export') != 1) {
            return view('components.export-modal', [
                'action' => route('administrator.tags.export'),
                'formats' => $allowedFormats,
            ]);
        }

        return $this->exportFile($request, new TagsExport($request->take), 'tags', $allowedFormats);
    }

    /**
     * Import Tags data.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function import(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('components.import-modal', [
                'action' => route('administrator.tags.import'),
                'dataTable' => '#tags-datatable',
            ]);
        }

        $allowedFormats = ['xlsx', 'csv'];

        return $this->importFile($request, new TagsImport, $allowedFormats);
    }

    /**
     * Search tags.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        $request->validate([
            'q' => 'required|string'
        ]);
        
        $tags = Tag::where('name', 'like', '%'.$request->get('q').'%')->paginate();

        return response()->json([
            'ok' => true,
            'message' => 'Success to get tags data.',
            'data' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Tag::class);

        return view('components.tag.form-modal');
    }

    /**
     * Format tag name.
     *
     * @param   string  $name
     *
     * @return  string
     */
    private function setTagName(string $name)
    {
        return preg_replace('/[^a-z0-9]+/', ' ', $name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TagRequest   $tagRequest
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $tagRequest)
    {
        $tag = Tag::create([
            'name' => $this->setTagName($tagRequest->name)
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Tag created.',
            'data' => $tag
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('components.tag.form-modal', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TagRequest   $tagRequest
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $tagRequest, Tag $tag)
    {
        $tag->update([
            'name' => $this->setTagName($tagRequest->name)
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Tag updated.',
            'data' => $tag
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Tag deleted.',
            'data' => $tag
        ]);
    }
}
