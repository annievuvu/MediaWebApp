<?php
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ActiveMiddleware;
use App\Http\Middleware\SessionTimeout;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
   
	Route::get('/', function () {
	    return view('welcome');
	});

	Route::get('/login','Auth\AuthController@getLogin');
    Route::post('/login','Auth\AuthController@postLogin');
    Route::get('/logout','Auth\AuthController@logOut');
    
    Route::group(['middleware' => [ActiveMiddleware::class,SessionTimeout::class]], function () {
		Route::get('/home',function(){
			return redirect('/rooms');
		});

		Route::get('/rooms',"AssetsController@roomStatus");
		Route::post('/rooms/find',"AssetsController@findRooms");

		Route::group(['middleware' => AdminMiddleware::class], function () {
			Route::get('/loan','LoansController@getLoanForm');
			Route::post('/loan','LoansController@postLoan');

			Route::get('/profile',"UsersController@profile");

			Route::get('/return',"LoansController@needReturning");
			Route::post('/return',"LoansController@return");

			Route::get('/issues',"IssuesController@listIssues");
			Route::get('/issues/{id}',"IssuesController@seeIssue");
			Route::post('/issues/{id}',"IssuesController@addComment");
			Route::post('/issues/edit/{id}',"IssuesController@editIssue");
			Route::get('/assets/{asset_id}/issues',"IssuesController@showIssues");

			Route::post('/roomcheck/',"AssetsController@clearRoom");


			Route::get('/roomcheck/comment/{room}/{selected}',"AssetsController@addComments");
			Route::post('/roomcheck/comment/{room}/{selected}',"IssuesController@submitComments");

			Route::get('/roomcheck/all', function() {
			    return App\Asset::room()->orderBy('barcode')->get();
			});

			Route::get('/roomcheck/{id}/equipments',function($id){
				return App\Asset::where("container_id",$id)->with('type')->get();
			});
			
			Route::get('/roomcheck/{id}/checked',"AssetsController@getTimeChecked");
			Route::get('/roomcheck/{id}/checkedBy',"AssetsController@getUser");


			Route::get('/roomcheck', "AssetsController@showRooms");

			Route::post('/roomcheck/all', function() {
			    return App\Asset::create(Request::all());
			});

			Route::group(['middleware' => AdminMiddleware::class], function () {
				Route::get('/users',"UsersController@index");
				Route::post('/users','UsersController@removeSelectedUser');
				Route::get('/users/add',"UsersController@addUser");
				Route::post('users/add','UsersController@postUser');
				Route::post('users/edit/{id}','UsersController@editUser');
				Route::get('users/reactivate','UsersController@reactivateUser');
				Route::post('users/reactivate','UsersController@reactivateSelectedUser');

				Route::get('/types',"TypesController@index");
				Route::post('/types',"TypesController@remoteType");
				Route::get('types/add','TypesController@addType');
				Route::post('types/add','TypesController@postType');
				Route::post('types/edit/{id}','TypesController@editType');

				Route::get('/assets',"AssetsController@index");
				Route::get('assets/add','AssetsController@addAsset');
				Route::post('assets/add','AssetsController@postAsset');
				Route::post('assets','AssetsController@removeAsset');
				Route::get('assets/rooms','AssetsController@rooms');

				Route::post('assets/edit/{id}','AssetsController@editAsset');
				Route::get('assets/{id}',"AttributeAssetController@showAttributes");
				Route::post('assets/{id}/add',"AttributeAssetController@linkAttributes");
				Route::post('assets/{id}/delete',"AttributeAssetController@removeAttributes");
				Route::post('assets/{id}/edit/{attribute}',"AttributeAssetController@editLink");

				Route::get('/attributes',"AttributesController@index");
				Route::post('/attributes',"AttributesController@removeAttribute");
				Route::get('/attributes/add','AttributesController@addAttribute');
				Route::post('/attributes/add','AttributesController@postAttribute');
				Route::post('/attributes/edit/{id}','AttributesController@editAttribute');
			});
		});
	});
});



