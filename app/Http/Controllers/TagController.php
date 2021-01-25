<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            return view('pages.tags', [
                'title' => 'Tags'
            ]);
        }

        return DataTables::of(Tag::query())
                            ->addColumn('actions', function (Tag $tag) {
                                $editButton = '<a href="'.route('administrator.tags.edit', ['tag' => $tag]).'" class="btn btn-warning btn-modal-trigger" data-modal="#form-tag-modal"><i class="fas fa-edit"></i></a>';
                                
                                $deleteButton = '<a href="'.route('administrator.tags.destroy', ['tag' => $tag]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#tags-datatable" data-item-name="'.$tag->name.'"><i class="fas fa-trash"></i></a>';

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $tag->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Tag deleted.',
            'data' => $tag
        ]);
    }
}
