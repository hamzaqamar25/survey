<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::any('survey/click','SurveyController@surveyStart');
//Route::any('survey/track','SurveyController@surveyTrack');

// survey intermediate - means we land on a survey page
Route::get('survey/intermediate/{id}','SurveyController@surveyStart');
// survey track and save in db, save
Route::get('survey/track/{id}','SurveyController@surveyTrack');


Route::any('{all}', function () {
    return view('app');
})->where(['all' => '.*']);
