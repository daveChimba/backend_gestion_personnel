<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'auth'], function () {

    Route::post('token', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::delete('token', 'AuthController@logout');
        Route::get('permissions', 'AuthController@permissions');
        Route::get('roles', 'AuthController@roles');
        Route::get('teams', 'AuthController@teams');

    });

});


//Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('/{id}', 'UserController@getUserInfo');
        Route::post('/', 'UserController@create');
        Route::match(['post', 'put'], '/{id}', 'UserController@update');
    });


    Route::group(['prefix' => 'blog_categories'], function () {
        Route::delete('/{id}', 'BlogCategoryController@delete');
        Route::get('/{id}', 'BlogCategoryController@find');
        Route::get('/', 'BlogCategoryController@get');
    });


    Route::group(['prefix' => 'assignments'], function () {
        Route::delete('/{id}', 'AssignmentController@delete');
        Route::get('/{id}', 'AssignmentController@find');
        Route::get('/', 'AssignmentController@get');
        Route::post('/', 'AssignmentController@create');
        Route::put('/{id}', 'AssignmentController@update');
    });



    Route::group(['prefix' => 'contracts'], function () {
        Route::post('/', 'ContractController@create');
        Route::post('/{id}', 'ContractController@update');
        Route::get('/{id}', 'ContractController@find');
        Route::get('/', 'ContractController@get');
        Route::delete('/{id}', 'ContractController@delete');
    });



    Route::group(['prefix' => 'divisions'], function () {
        Route::post('/', 'DivisionController@create');
        Route::put('/{id}', 'DivisionController@update');
        Route::get('/', 'DivisionController@get');
        Route::get('/{id}', 'DivisionController@find');
        Route::delete('/{id}', 'DivisionController@delete');
    });

    Route::group(['prefix' => 'note_criterias'], function () {
        Route::post('/', 'NoteCriteriaController@create');
        Route::put('/{id}', 'NoteCriteriaController@update');
        Route::get('/{id}', 'NoteCriteriaController@find');
        Route::get('/', 'NoteCriteriaController@get');
        Route::delete('/{id}', 'NoteCriteriaController@delete');
    });

    Route::group(['prefix' => 'vacation_types'], function () {
        Route::get('', 'VacationTypeController@get');
        Route::get('/{id}', 'VacationTypeController@find');
        Route::delete('/{id}', 'VacationTypeController@delete');
    });

    Route::group(['prefix' => 'disciplinary_boards'], function () {
        Route::get('/', 'DisciplinaryBoardController@get');
        Route::get('/{id}', 'DisciplinaryBoardController@find');
        Route::delete('/{id}', 'DisciplinaryBoardController@delete');
    });

    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::post('/', 'DisciplinaryTeamController@create');
        Route::put('/{id}', 'DisciplinaryTeamController@update');
    });

    Route::group(['prefix' => 'disciplinary_boards'], function () {
        Route::post('/', 'DisciplinaryBoardController@create');
        Route::put('/{id}', 'DisciplinaryBoardController@update');
    });

    Route::group(['prefix' => 'assignments'], function () {
        Route::post('/', 'AssignmentController@create');
        Route::put('/{id}', 'AssignmentController@update');
    });

    Route::group(['prefix' => 'assignments'], function () {
        Route::post('/', 'AssignmentController@create');
        Route::put('/{id}', 'AssignmentController@update');
    });

    Route::group(['prefix' => 'licenses'], function () {
        Route::get('/', 'LicenseController@get');
        Route::delete('{id}', 'LicenseController@delete');
        Route::get('{id}', 'LicenseController@find');
        Route::delete('lchangeStatus/{id}', 'LicenseController@changeStatus');
    });

    Route::group(['prefix' => 'licenses'], function () {
        Route::get('/', 'LicenseController@get');
        Route::delete('{id}', 'LicenseController@delete');
        Route::get('{id}', 'LicenseController@find');
        Route::delete('lchangeStatus/{id}', 'LicenseController@changeStatus');
    });

    Route::group(['prefix' => 'blog_posts'], function () {
        Route::get('/', 'BlogPostController@get');
        Route::delete('{id}', 'BlogPostController@delete');
        Route::get('{id}', 'BlogPostController@find');
    });


    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::get('/{id}', 'DisciplinaryTeamController@find');
        Route::get('/', 'DisciplinaryTeamController@get');
        Route::delete('/{id}', 'DisciplinaryTeamController@delete');
    });

    Route::group(['prefix' => 'templates'], function () {
        Route::post('/', 'TemplateController@create');
        Route::put('/{id}', 'TemplateController@update');
    });

    //il s'agit des routes pour read et delete profile
    Route::group(['prefix' => 'profiles'], function () {
        Route::get('/', 'ProfileController@get');
        Route::get('/{id}', 'ProfileController@find');
        Route::delete('/{id}', 'ProfileController@delete');
    });

    //sanctions
    Route::group(['prefix' => 'sanctions'], function () {
        Route::post('/', 'SanctionController@create');
        Route::put('/{id}', 'SanctionController@update');
        Route::get('/', 'SanctionController@get');
        Route::get('/{id}', 'SanctionController@find');
        Route::delete('/{id}', 'SanctionController@delete');
    });

    //career read and delete
    Route::group(['prefix' => 'careers'], function () {
        Route::get('/', 'CareerController@get');
        Route::get('/{id}', 'CareerController@find');
        Route::delete('/{id}', 'CareerController@delete');
        Route::post('/', 'CareerController@create');
        Route::match(['put', 'post'], '/{id}', 'CareerController@update');
    });


    Route::group(['prefix' => 'trainings'], function () {
        Route::get('/', 'TrainingController@get');
        Route::get('/{id}', 'TrainingController@find');
        Route::delete('/{id}', 'TrainingController@delete');
        Route::post('/', 'TrainingController@create');
        Route::put('/{id}', 'TrainingController@update');
    });


    Route::group(['prefix' => 'license_types'], function () {
        Route::get('/', 'LicenseTypeController@get');
        Route::delete('/{id}', 'LicenseTypeController@delete');
        Route::get('/{id}', 'LicenseTypeController@find');
    });

    Route::group(['prefix' => 'vacations'], function () {
        Route::get('/', 'VacationController@get');
        Route::delete('/{id}', 'VacationController@delete');
        Route::get('/{id}', 'VacationController@find');
        Route::post('/', 'VacationController@create');
        Route::match(['post', 'put'], '/{id}', 'VacationController@update');
    });

    Route::group(['prefix' => 'disciplinary_teams'], function () {
        Route::post('/', 'DisciplinaryTeamController@create');
        Route::match(['put', 'post'], '/{id}', 'DisciplinaryTeamController@update');
    });


    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RoleController@get');
        Route::post('/', 'RoleController@store');
        Route::post('/{id}', 'RoleController@update');
        Route::delete('/{id}', 'RoleController@delete');
        Route::get('/{id}', 'RoleController@find');
    });

    Route::get('/permissions', 'RoleController@getPermissions');


    Route::group(['prefix' => 'profile_updates'], function () {
        Route::get('/', 'ProfileUpdateController@get');
        Route::get('{id}', 'ProfileUpdateController@find');
        Route::delete('{id}', 'ProfileUpdateController@delete');
        Route::post('/', 'ProfileUpdateController@create');
        Route::post('/{id}', 'ProfileUpdateController@update');
    });


    Route::group(['prefix' => 'blog_categories'], function () {
        Route::post('/', 'BlogCatController@create');
        Route::post('/{id}', 'BlogCatController@update');
    });


    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/{id}', 'ContactController@find');
        Route::get('/', 'ContactController@get');
        Route::delete('/{id}', 'ContactController@delete');
    });

//});





