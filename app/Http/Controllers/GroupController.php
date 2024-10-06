<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $groups = Group::query();

        $groups->when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });

        $groups = $groups->latest()->paginate(10);

        if (request()->wantsJson()) {
            return GroupResource::collection($groups);
        }
        return view('groups.index')->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupStoreRequest $request)
    {
        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('groups', 'public');
        }

        $group = Group::create([
            'name' => $request->name,
            'image' => $image_path,
            'status' => $request->status
        ]);

        if (!$group) {
            return redirect()->back()->with('error', __('group.error_creating'));
        }
        return redirect()->route('groups.index')->with('success', __('group.success_creating'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('groups.edit')->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->name = $request->name;
        $group->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($group->image) {
                Storage::delete($group->image);
            }
            // Store image
            $image_path = $request->file('image')->store('groups', 'public');
            // Save to Database
            $group->image = $image_path;
        }        

        if (!$group->save()) {
            return redirect()->back()->with('error', __('group.error_updating'));
        }
        return redirect()->route('groups.index')->with('success', __('group.success_updating'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        
        if ($group->image) {
            Storage::delete($group->image);
        }
        
        $group->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
