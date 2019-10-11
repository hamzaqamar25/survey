<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publisher;
use App\User;
use Ramsey\Uuid\Uuid;

class PublisherController extends Controller
{
	public function getPublisher(Request $request,$id)
	{
		$publisher = Publisher::find($id);

		return response()->json(compact('publisher'));
	}

	public function updatePublisher(Request $request)
	{
		$id = $request->input('id');

		$publisher = Publisher::find($id);
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',
			'supcode'      => 'required',
			'confirm_new_password' => 'same:new_password'
		];

		$this->validate($request, $rules);
		
		$publisher->name = $request->input('name');
		$publisher->location = $request->input('location');
		$publisher->supcode = $request->input('supcode');

		$publisher->phone_no = $request->input('phone_no');
		$publisher->email = $request->input('email');
		$publisher->status = $request->input('status');
		if($publisher->p_uuid == '0') {
			$uuid1 = Uuid::uuid1();
			$publisher->p_uuid = $uuid1;
		}
		$publisher->save();

		if(!empty($request->input('confirm_new_password'))) {
			$user = User::find($publisher->user_id);
			$user->password = bcrypt($request->input('confirm_new_password'));
			$user->save();
		}
		return response()->json(compact('publisher'));
	}

	public function addPublisher(Request $request)
	{
		$rules = [
			'name'             => 'required',
			'email'            => 'required|string|email|max:255|unique:users',
			'phone_no'         => 'required',
			'location'         => 'required',
			'supcode'     	   => 'required',
			'password'         => 'required',
			'confirm_password' => 'required|same:password'
		];

		$this->validate($request, $rules);
		$uuid1 = Uuid::uuid1();
		$user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'ispublisher' =>1
        ]);
        
		$publisher = Publisher::create([
            'name'              => $request->input('name'),
            'p_uuid'            => $uuid1,
            'email'             => $request->input('email'),
            'phone_no'          => $request->input('phone_no'),
            'location'          => $request->input('location'),
            'supcode'          => $request->input('supcode'),
            'status'            => 1,
            'user_id'           => $user->id,
        ]);

        $publisher->save();


		return response()->json(compact('publisher'));
	}

	public function getAllPublisher(Request $request)
	{
		$publisher = Publisher::all();

		return response()->json(compact('publisher'));
	}
}