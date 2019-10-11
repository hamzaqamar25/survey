<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('authenticate', 'AuthenticateController@authenticate');

Route::group(['middleware' => 'jwt.auth'], function()
{
    Route::get('user', 'UserController@show');
    Route::post('user/password/update', 'UserController@updatePassword');
    Route::post('user/profile/update', 'UserController@updateProfile');

    Route::get('publisher/getAll','PublisherController@getAllPublisher');
    Route::get('publisher/get/{id}','PublisherController@getPublisher');
    Route::post('publisher/update','PublisherController@updatePublisher');
    Route::post('publisher/add', 'PublisherController@addPublisher');

    Route::get('client/getAll','ClientController@getAllClient');
    Route::get('client/get/{id}','ClientController@getClient');
    Route::post('client/update','ClientController@updateClient');
    Route::post('client/add', 'ClientController@addClient');

    Route::get('survey/getAll', 'SurveyController@getAllSurvey');
    Route::get('survey/get/{id}','SurveyController@getSurvey');
    Route::post('survey/update','SurveyController@updateSurvey');
    Route::post('survey/add', 'SurveyController@addSurvey');
    Route::post('survey/getSurveyReport', 'SurveyController@getSurveyReport');
    Route::post('survey/getSurveyDetailReport', 'SurveyController@getSurveyDetailReport');
    Route::get('survey/getSurveyPublisher/{id}','SurveyController@getSurveyPublisher');
    
});
