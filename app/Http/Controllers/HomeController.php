<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $member = \App\Member::orderBy('id', 'desc')->get();
        return view('home', ['member' => $member]);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            try {
                $store = \App\Member::create([
                    'name'    => $input['name'],
                    'age'     => $input['age'],
                    'address' => $input['address'],
                ]);
                $resp = $store;
            } catch (\Exception $e) {
                $resp = $e->getMessage()." ".$e->getFile()." ".$e->getLine();
                \Log::error(json_encode(['error store data' => $resp]));
            }
            
            $member = \App\Member::orderBy('id', 'desc')->get();
            return Response::json($member);
        }
    }

    public function edit($id)
    {
        $resp = \App\Member::find($id);
        return $resp;
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            try {

                $update = \App\Member::find($input['id'])->update([
                    'name'    => $input['name'],
                    'age'     => $input['age'],
                    'address' => $input['address'],
                ]);
                $resp = $update;
            } catch (\Exception $e) {
                $resp = $e->getMessage()." ".$e->getFile()." ".$e->getLine();
                \Log::error(json_encode(['error update data' => $resp]));
            }
            
            $member = \App\Member::orderBy('id', 'desc')->get();
            return Response::json($member);
        }
    }    
}
