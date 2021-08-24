<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all group list
        $groups = Group::all();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'groups' => $groups,
            ],
        ]);

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
        //Add Group
        try {
            //code...
            if (Group::where('name', $request->get('name'))->first()) {
                return response()->json([
                    'response' => [
                        'code' => 400,
                        'api_status' => 0,
                        'message' => "Group Name aready exist.",
                    ],
                ], 400);

            }
            $group = new Group;
            $avatar = $request->file('avatar');
            if ($avatar) {
                $destinationPath = 'uploads';
                $filename = "GroupAvatar_" . date('Y-m-d_H-i-s') . "." . $avatar->getClientOriginalExtension();
                $avatar->move($destinationPath, $filename);
                $group->avatar = "/uploads/$filename";
            }

            $group->name = $request->get('name');
            $group->canProvide = $request->get('canProvide');
            $group->canCopy = $request->get('canCopy');
            $group->canSee = $request->get('canSee');
            $group->save();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'message' => 'Successfully Added',
                ],
            ]);

        } catch (Exception $err) {
            //throw $th;
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Create Group Fialed.",
                ],
            ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update group
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Group not found",
                ],
            ], 400);
        }
        if (Group::where('id', '!=', $id)->where('name', $request->get('name'))->first()) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Group Name aready exist.",
                ],
            ], 400);

        }

        try {
            //code...

            $group->name = $request->get('name');
            $group->canProvide = $request->get('canProvide');
            $group->canCopy = $request->get('canCopy');
            $group->canSee = $request->get('canSee');
            $avatar = $request->file('avatar');
            if ($avatar) {
                $destinationPath = 'uploads';
                $filename = "GroupAvatar_" . date('Y-m-d_H-i-s') . "." . $avatar->getClientOriginalExtension();
                $avatar->move($destinationPath, $filename);
                $group->avatar = "/uploads/$filename";
            }
            $group->save();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'message' => 'Successfully Updated.',
                    'name' => $request->get('name'),
                    'group' => $group,
                ],
            ]);

        } catch (Exception $err) {
            //throw $th;
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Update Fialed.",
                ],
            ]);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete group
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Group not found",
                ],
            ], 400);
        }
        $group->delete();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "Successfully deleted.",
            ],
        ]);

    }
}