<?php

use Illuminate\Support\Facades\Route;

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

// Admin
Route::group(['middleware' => ['admin']], function(){
    // Logout
	Route::post('/admin/logout', 'LoginController@logout')->name('admin.logout');

	// Dashboard
	Route::get('/admin', 'DashboardController@index')->name('admin.dashboard');

    // Attendance
	Route::get('/admin/attendance', 'AttendanceController@index')->name('admin.attendance.index');
	Route::get('/admin/attendance/summary', 'AttendanceController@summary')->name('admin.attendance.summary');
	Route::get('/admin/attendance/create', 'AttendanceController@create')->name('admin.attendance.create');
	Route::post('/admin/attendance/store', 'AttendanceController@store')->name('admin.attendance.store');
	Route::get('/admin/attendance/detail/{id}', 'AttendanceController@detail')->name('admin.attendance.detail');
	Route::get('/admin/attendance/edit/{id}', 'AttendanceController@edit')->name('admin.attendance.edit');
	Route::post('/admin/attendance/update', 'AttendanceController@update')->name('admin.attendance.update');
	Route::post('/admin/attendance/delete', 'AttendanceController@delete')->name('admin.attendance.delete');

    // Absent
	Route::get('/admin/absent', 'AbsentController@index')->name('admin.absent.index');
	Route::get('/admin/absent/create', 'AbsentController@create')->name('admin.absent.create');
	Route::post('/admin/absent/store', 'AbsentController@store')->name('admin.absent.store');
	Route::get('/admin/absent/edit/{id}', 'AbsentController@edit')->name('admin.absent.edit');
	Route::post('/admin/absent/update', 'AbsentController@update')->name('admin.absent.update');
	Route::post('/admin/absent/delete', 'AbsentController@delete')->name('admin.absent.delete');

	// User
	Route::get('/admin/user', 'UserController@index')->name('admin.user.index');
	Route::get('/admin/user/create', 'UserController@create')->name('admin.user.create');
	Route::post('/admin/user/store', 'UserController@store')->name('admin.user.store');
	Route::get('/admin/user/detail/{id}', 'UserController@detail')->name('admin.user.detail');
	Route::get('/admin/user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
	Route::post('/admin/user/update', 'UserController@update')->name('admin.user.update');
	Route::get('/admin/user/edit-indicator/{id}', 'UserController@editIndicator')->name('admin.user.edit-indicator');
	Route::post('/admin/user/update-indicator', 'UserController@updateIndicator')->name('admin.user.update-indicator');
	Route::post('/admin/user/delete', 'UserController@delete')->name('admin.user.delete');

	// Group
	Route::get('/admin/group', 'GroupController@index')->name('admin.group.index');
	Route::get('/admin/group/create', 'GroupController@create')->name('admin.group.create');
	Route::post('/admin/group/store', 'GroupController@store')->name('admin.group.store');
	Route::get('/admin/group/detail/{id}', 'GroupController@detail')->name('admin.group.detail');
	Route::get('/admin/group/edit/{id}', 'GroupController@edit')->name('admin.group.edit');
	Route::post('/admin/group/update', 'GroupController@update')->name('admin.group.update');
	Route::post('/admin/group/delete', 'GroupController@delete')->name('admin.group.delete');

	// Office
	Route::get('/admin/office', 'OfficeController@index')->name('admin.office.index');
	Route::get('/admin/office/create', 'OfficeController@create')->name('admin.office.create');
	Route::post('/admin/office/store', 'OfficeController@store')->name('admin.office.store');
	Route::get('/admin/office/detail/{id}', 'OfficeController@detail')->name('admin.office.detail');
	Route::get('/admin/office/edit/{id}', 'OfficeController@edit')->name('admin.office.edit');
	Route::post('/admin/office/update', 'OfficeController@update')->name('admin.office.update');
	Route::post('/admin/office/delete', 'OfficeController@delete')->name('admin.office.delete');

	// Position
	Route::get('/admin/position', 'PositionController@index')->name('admin.position.index');
	Route::get('/admin/position/create', 'PositionController@create')->name('admin.position.create');
	Route::post('/admin/position/store', 'PositionController@store')->name('admin.position.store');
	Route::get('/admin/position/detail/{id}', 'PositionController@detail')->name('admin.position.detail');
	Route::get('/admin/position/edit/{id}', 'PositionController@edit')->name('admin.position.edit');
	Route::post('/admin/position/update', 'PositionController@update')->name('admin.position.update');
	Route::post('/admin/position/delete', 'PositionController@delete')->name('admin.position.delete');

	// Work Hour
	Route::get('/admin/work-hour', 'WorkHourController@index')->name('admin.work-hour.index');
	Route::get('/admin/work-hour/create', 'WorkHourController@create')->name('admin.work-hour.create');
	Route::post('/admin/work-hour/store', 'WorkHourController@store')->name('admin.work-hour.store');
	Route::get('/admin/work-hour/edit/{id}', 'WorkHourController@edit')->name('admin.work-hour.edit');
	Route::post('/admin/work-hour/update', 'WorkHourController@update')->name('admin.work-hour.update');
	Route::post('/admin/work-hour/delete', 'WorkHourController@delete')->name('admin.work-hour.delete');

	// Salary Category
	Route::get('/admin/salary-category', 'SalaryCategoryController@index')->name('admin.salary-category.index');
	Route::get('/admin/salary-category/create', 'SalaryCategoryController@create')->name('admin.salary-category.create');
	Route::post('/admin/salary-category/store', 'SalaryCategoryController@store')->name('admin.salary-category.store');
	Route::get('/admin/salary-category/edit/{id}', 'SalaryCategoryController@edit')->name('admin.salary-category.edit');
	Route::post('/admin/salary-category/update', 'SalaryCategoryController@update')->name('admin.salary-category.update');
	Route::get('/admin/salary-category/set/{id}', 'SalaryCategoryController@set')->name('admin.salary-category.set');
	Route::post('/admin/salary-category/update-indicator', 'SalaryCategoryController@updateIndicator')->name('admin.salary-category.update-indicator');
	Route::post('/admin/salary-category/delete', 'SalaryCategoryController@delete')->name('admin.salary-category.delete');

	// // Salary Indicator
	// Route::get('/admin/salary-indicator', 'SalaryIndicatorController@index')->name('admin.salary-indicator.index');
	// Route::get('/admin/salary-indicator/create', 'SalaryIndicatorController@create')->name('admin.salary-indicator.create');
	// Route::post('/admin/salary-indicator/store', 'SalaryIndicatorController@store')->name('admin.salary-indicator.store');
	// Route::get('/admin/salary-indicator/edit/{id}', 'SalaryIndicatorController@edit')->name('admin.salary-indicator.edit');
	// Route::post('/admin/salary-indicator/update', 'SalaryIndicatorController@update')->name('admin.salary-indicator.update');
	// Route::post('/admin/salary-indicator/delete', 'SalaryIndicatorController@delete')->name('admin.salary-indicator.delete');
});

// Member
Route::group(['middleware' => ['member']], function(){
    // Logout
	Route::post('/member/logout', 'LoginController@logout')->name('member.logout');

	// Dashboard
    Route::get('/member', 'DashboardController@index')->name('member.dashboard');

	// Attendance
	Route::get('/member/attendance/detail', 'AttendanceController@detail')->name('member.attendance.detail');
	Route::post('/member/attendance/entry', 'AttendanceController@entry')->name('member.attendance.entry');
	Route::post('/member/attendance/exit', 'AttendanceController@exit')->name('member.attendance.exit');
});

// Guest
Route::group(['middleware' => ['guest']], function(){
    // Home
    Route::get('/', function () {
		// $routes = collect(Route::getRoutes())->map(function($route) {
		// 	return $route->uri();
		// });

		// echo "<pre>";
		// var_dump($routes);
		// echo "</pre>";
		// return;

        return redirect()->route('auth.login');
    });

    // Login
    Route::get('/login', 'LoginController@show')->name('auth.login');
    Route::post('/login', 'LoginController@authenticate')->name('auth.post-login');
});