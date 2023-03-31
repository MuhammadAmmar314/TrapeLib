<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return response(view('admin.member'));
    }

    public function api(Request $request)
    {

        if($request->gender) {
            $members = Member::where('gender' , $request->gender)->get();
        } else {
            $members = Member::all();
        }
        
        $datatables = datatables()->of($members)->addIndexColumn();
        
        return $datatables->make(true);
    }
    /**
     * Show the form for creating a new resource.x
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
        $this->validate($request, [
            'name' => ['required' , 'string' , 'min:5'], 
            'gender' => ['required' , 'string' , 'max:1'],
            'phone_number' => ['required' , 'numeric'],
            'address' => ['required'],
            'email' => ['required'],
        ]);

        Member::create($request->all());

        return response(redirect('members'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => ['required' , 'string' , 'min:5'], 
            'gender' => ['required' , 'string' , 'max:1'],
            'phone_number' => ['required' , 'numeric'],
            'address' => ['required'],
            'email' => ['required'],
        ]);

        $member->update($request->all());

        return response(redirect('members'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
    }
}
