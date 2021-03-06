<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Loan as Loan;
use App\Asset as Asset;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class LoansController extends Controller
{
    public function getLoanForm(){
    	$rooms = Asset::room()->enabled()->orderBy('barcode')->get();
    	$items = Asset::loanable()->enabled()->orderBy('barcode')->get();
    	return view('loan',['rooms' => $rooms,'items'=>$items]);
    }

    public function return(Request $request){
        $loan = Loan::findOrFail($request->id);
        $loan->is_returned = 1;
        return response()->json(['return' => $loan->save()]);
    }

    public function postLoan(Request $request){

        $validator = Validator::make($request->all(), [
            'item' => 'required',
            'due' => 'required|string',
            'email' => 'string',
            'loaned' => 'required|string',
            'room' => 'required',
            'comments' => 'string',
        ]);


        if ($validator->fails()) {
            return redirect('/loan')
                ->withInput()
                ->withErrors($validator);
        }

        // DB::table('loans')->insert([ //,
        //     'asset_id' => $request->item,
        //     'user_id' => $request->user()->id,
        //     'comment_before' => $request->comments,
        //     'due' => $request->due,
        //     'is_returned' => false,
        //     'email' => $request->email,
        //     'loaned_to' => $request->loaned,
        //     'container_id' => $request->room,
        //     'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        //     'update_at' => \Carbon\Carbon::now()->toDateTimeString(),
        // ]);


        $loan = new Loan;
        // $asset = array(Asset::where('barcode',$request->item)->first(['id']));
        $loan->asset_id = $request->item;
        $loan->user_id = $request->user()->id;
        if(isset($request->comments))$loan->comment_before = $request->comments;
        else $loan->comment_before = "";
        $time = strtotime($request->due);
        $loan->due = date("Y-m-d H:i:s", $time);
        $loan->is_returned = false;
        $loan->email = $request->email;
        $loan->loaned_to = $request->loaned;
        $loan->container_id = $request->room;

        $loan->save();

        return redirect('/loan');
    }

    public function needReturning(){
        $items = Loan::where('is_returned',0)->get();
        // print_r($items);
        return view('return',['items'=>$items]);
    }
}
