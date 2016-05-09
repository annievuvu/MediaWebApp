<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use DB;

class UsersController extends Controller
{
    public function index(){
    	$users = User::Active()->orderBy('id', 'DESC')->get();
    	return view('user.users',['users'=>$users]);
    }

    public function profile(){
    	$user = Auth::user();
    	return view('user.profile',['user'=>$user]);
    }

    public function addUser(){
    	return view('user.addUser');
    }

    public function postUser(Request $request){
        
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'phonenum' => 'required',
            'authorized' => 'required',
            'user' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/users/add')
                ->withInput()
                ->withErrors($validator);
        }

        $user = new User;
        $user->first_name = $request->fname;
        $user->last_name = $request->lname;
        $user->phone_num = $request->phonenum;
        $user->is_authorized = $request->authorized;
        $user->user = $request->user;
        $user->save();

        return redirect('/users');
    }

    public function removeSelectedUser(Request $request){
        // $user = User::find($request->id);
        // $user->update(array('active'=>0));
        // //return redirect('/users/remove');
        // return redirect('/users');
        // $data = Input::all();        
        $user = User::find($request->id);
        // print_r($user);
        $user->update(['active'=>0]);
        // return view('assets.assets');    
        return response()->json(['return' => $request->id]);
    }

    public function editUser(Request $request, $id) {
        $user = User::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $correct_username = true;
        if($name == 'user'){
            if($user->is_authorized){
                $yay = preg_match('/N\d{8}/',$value);
            }
            else{
                $yay = preg_match('/\d{14}/',$value);
            }
            if($yay == 0 || $yay == FALSE) $correct_username == false;
            $value = strval($value);
        }
        return $yay;
        if (!$correct_username)return response()->json(['status'=> 'error']);
        $user->$name = $value;
        return response()->json(['return' => $user->save()]);
    }

    public function reactivateUser(){
        $users = User::Inactive()->get();
        return view('user.reactivateUser',['users'=>$users]);
    }

    public function reactivateSelectedUser(Request $request){
        $user = User::find($request->id);
        $user->update(['active'=>1]);
        return response()->json(['return' => $request->id]);
    }
}
