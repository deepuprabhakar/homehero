<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('admin');
});

Route::get('/home', function () {
    return redirect('admin/dashboard');
});

/**
 * ============
 * Admin Routes
 * ============
 */
Route::get('admin/login', 'AdminAuth\AuthController@showLoginForm');
Route::post('admin/login', 'AdminAuth\AuthController@login');
Route::match(['GET', 'POST'], 'admin/logout', 'AdminAuth\AuthController@logout');

Route::get('/admin/password/reset/{token?}','AdminAuth\PasswordController@showResetForm');
Route::post('/admin/password/reset','AdminAuth\PasswordController@reset');
Route::post('/admin/password/email','AdminAuth\PasswordController@sendResetLinkEmail');


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['admin', 'history:admin']], function() {

    Route::get('dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    Route::get('/', function(){
    	return redirect()->route('admin.dashboard');
    });

    Route::get('change-password', 'AdminController@getChangePasswordForm')->name('admin.change.password');
    Route::post('change-password', 'AdminController@savePassword')->name('admin.password.save');

    /**
     * Admin CRUD routes
     */
    Route::resource('admins', 'AdminController');

    Route::post('admin/admins/list', 'AdminController@getAdmins')->name('admins.list');

    /**
     * Staff Routes
     */
    Route::resource('staff', 'StaffController');

    Route::post('admin/staff/list', 'StaffController@getStaff')->name('staff.list');

    /**
     * Client Routes
     */
    Route::resource('clients', 'ClientController');

    Route::post('admin/clients/list', 'ClientController@getClients')->name('clients.list');

    /**
     * Location Routes
     */
    Route::resource('locations', 'LocationController');

    Route::post('admin/locations/list', 'LocationController@getLocations')->name('locations.list');

    /**
     * Room Routes
     */
    Route::resource('rooms', 'RoomController');

    Route::post('admin/rooms/list', 'RoomController@getRooms')->name('rooms.list');

    /**
     * WorkItem Routes
     */
    Route::resource('work-items', 'WorkItemController');

    Route::post('admin/work-items/list', 'WorkItemController@getWorkItems')->name('work-items.list');

    Route::post('admin/item-price', 'WorkItemController@getItemPrice')->name('item.price');

    /**
     * WorkItem Routes
     */
    Route::resource('parts', 'PartController');

    Route::post('admin/parts/list', 'PartController@getParts')->name('parts.list');

    Route::post('admin/part-price', 'PartController@getPartPrice')->name('part.price');
    
    /**
     * Steps Routes
     */
    Route::resource('item-steps', 'StepsController');

    Route::post('admin/item-steps/list', 'StepsController@getSteps')->name('item-steps.list');

    /**
     * Item Notes Routes
     */
    Route::resource('item-notes', 'NotesController');

    Route::post('admin/item-notes/list', 'NotesController@getNotes')->name('item-notes.list');

    /**
     * Proposal Routes
     */
    Route::post('proposals/approve', 'ProposalController@approve')->name('proposals.approve');

    Route::resource('proposals', 'ProposalController');

    Route::post('admin/proposals/list', 'ProposalController@proposals')->name('proposals.list');

    Route::post('admin/proposals/list/{id}', 'ProposalController@details')->name('proposals.details');

    Route::get('admin/proposals/{id}/download', 'ProposalController@download')->name('proposals.download');

    Route::post('admin/add-task', 'ProposalController@addTask')->name('task.add');
    Route::get('admin/remove-task/{id}', 'ProposalController@removeTask')->name('task.remove');

    Route::get('proposals/{id}/versions', 'ProposalController@versions')->name('proposals.versions');
    Route::post('proposals/send', 'ProposalController@send')->name('proposals.send');
    Route::get('proposals/{id}/versions/list', 'ProposalController@versionsList')->name('proposals.versions.list');

    Route::get('admin/items', 'ProposalController@getItems')->name('items.list');

    /**
     *  Types Routes
     */
    Route::resource('types', 'TypesController');

    Route::post('admin/types/list', 'TypesController@getTypes')->name('types.list');

    /**
     *  Types Routes
     */
    Route::resource('sub-types', 'SubTypeController');

    Route::post('admin/sub-types/list', 'SubTypeController@getTypes')->name('sub-types-2.list');

    /**
     *  Sub Types list
     */
    Route::get('ajax-sub-types', 'TypesController@getSubTypes')->name('sub-types.list');

    /*Route::get('users', 'UserController@index')->name('users.index');
    Route::get('users/{id}', 'UserController@show')->name('users.show');

    Route::get('admin/users/list', 'UserController@list')->name('users.list');*/

    Route::post('proposal-edit-get-parts', 'ProposalController@getParts')->name('proposal-edit-get-parts');
    Route::post('proposal-edit-get-parts-2', 'ProposalController@getParts2')->name('proposal-edit-get-parts-2');
    Route::post('proposal-edit-get-steps', 'ProposalController@getSteps')->name('proposal-edit-get-steps');

});

/**
 * ==========
 * API Routes
 * ==========
 */

Route::group(['prefix' => 'api/ver1.0/staff', 'namespace' => 'Api'], function() {

    Route::post('login', 'ApiController@login');
    Route::post('multimail', 'ApiController@multiMail');

    Route::post('reset/password', 'ApiController@resetPassword');

    Route::get('services', 'DataController@getData');

    // Adding JWT Auth Middleware to prevent invalid access
    Route::group(['middleware' => ['jwt.auth', 'cors']], function()   { 
    // Route::group(['middleware' => []], function()   {

        // Client routes for APi
        Route::resource('clients', 'ClientController',
            ['only' =>
                [
                    'index', 'show', 'store', 'edit', 'update'
                ]
            ]);

        Route::post('proposals/update', 'ProposalController@update')->name('api.proposals.update');

        // Proposal routes for APi
        Route::resource('proposals', 'ProposalController',
            ['only' =>
                [
                    'index', 'show', 'store', 'edit'
                ]
            ]);

        // Get all data from database
        // Route::get('services', 'DataController@getData');

        Route::post('upload-media', 'ProposalController@uploadMedia');

    });

});

/**
 * ============
 * User Routes
 * ============
 */
// Route::auth();

// Route::get('/home', 'HomeController@index');

/*Route::get('pdf', function(){
    $pdf = PDF::loadHTML('<h1>PDF</h1>');
    return $pdf->download('test.pdf');
});
*/
