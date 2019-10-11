<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
	public function getClient(Request $request,$id)
	{
		$client = Client::find($id);

		return response()->json(compact('client'));
	}

	public function updateClient(Request $request)
	{
		$id = $request->input('id');
		
		$client = Client::find($id);
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',

		];

		$this->validate($request, $rules);
		
		$client->name = $request->input('name');
		$client->location = $request->input('location');
		$client->phone_no = $request->input('phone_no');
		$client->email = $request->input('email');
		$client->status = $request->input('status');
		$client->save();

		return response()->json(compact('client'));
	}

	public function addClient(Request $request)
	{
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',

		];

		$this->validate($request, $rules);

		$client = Client::create([
            'name'             => $request->input('name'),
            'email'             => $request->input('email'),
            'phone_no'             => $request->input('phone_no'),
            'location'             => $request->input('location'),
            'status'          => 1,
        ]);

        $client->save();

		return response()->json(compact('client'));
	}

	public function getAllClient(Request $request)
	{
		$client = Client::all();

		return response()->json(compact('client'));
	}
}