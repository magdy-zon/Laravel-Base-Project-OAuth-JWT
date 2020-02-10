<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $primary_users  = User::where('cat_type_user_id', 1)->get();
        $all_users      = array();
        foreach ($primary_users as $user) {
            $p_ubication    = $user->ubication
            $all_secondary  = $user->secondary_users;

            $all_secondary_users = array();
            foreach ($all_secondary as $s_user) {
                $secondary['secondary_user']  = User::find($s_user->fk_secondary->user);
                $secondary['ubication']       = $s_user->ubication;
                $all_secondary_users[]        = $secondary;
            }

            $primary['primary_user']    = $user;
            $primary['ubication']       = $p_ubication;
            $primary['secondary_users'] = $all_secondary_users;

            $all_users[] = $primary;
        }
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
        $user = User::where('id', $request->user_id)->update($request->type_user);
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
}
