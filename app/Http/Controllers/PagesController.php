<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('items.index');
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
     	$mainDb = [];
		$row = [];
		$mainDb = Storage::disk('dbjson')->exists('db.json') 
		? json_decode(Storage::disk('dbjson')->get('db.json')) 
		: [];
      	$row = $request->only(['product_name', 'quantity', 'price']);
      	$row['created_at'] = date('Y-m-d H:i:s');
      	
      	$lastId = 1;

      	if(!empty($mainDb)) {
  		  $lastrow = end($mainDb);	
  		  $lastId = $lastrow->id;
  		  $lastId++;
      	} 
      	$row['id'] = $lastId;
      	array_push($mainDb, $row);
        Storage::disk('dbjson')->put('db.json', json_encode($mainDb));
        return $mainDb;
    }

   	public function ajaxData() {
   		$mainDb = [];
   		$mainDb = Storage::disk('dbjson')->exists('db.json') 
		? json_decode(Storage::disk('dbjson')->get('db.json')) 
		: [];

		return $mainDb;
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
        //
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
