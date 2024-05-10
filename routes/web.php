<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\ContacttypeController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('signup');

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/','getDashboard')->name('dashboard');
        Route::get('/payable-receivable-chart','payRecLineChart')->name('dashboard.payreceivechart');
    });

    
    //roles and permissons
    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::get('/edit-assigned-role/{id}', 'editAddAssignedRole')->name('roles.editAssign');
        Route::post('/edit-assigned-role', 'updateAssignedRoles')->name('roles.updateAssign');
        Route::get('/assign-role','assign')->name('role.assign');
    });
    Route::resource('roles', RoleController::class);
  

    Route::get('/login', [UserController::class, 'login'])->name('login')->withoutMiddleware('auth');
    Route::post('/login', [UserController::class, 'authenticate'])->name('signup')->withoutMiddleware('auth');
    //users
    Route::resource('user',UserController::class);
    Route::controller(UserController::class)->prefix('users')->group(function(){
        Route::get('/logout','logout')->name('user.logout');
        Route::post('/register',  'register')->name('user.register');
        Route::get('/assign-project-user/{id}', 'assignProjectToUserForm')->name('user.assign.project.create');
        Route::post('/assign-project-user', 'assignProjectToUser')->name('user.assign.project.store');
        Route::get('/assign-contacttype-user/{id}', 'assignContactTypeToUserForm')->name('user.assign.ctype.create');
        Route::post('/assign-contacttype-user', 'assignContactTypeToUser')->name('user.assign.ctype.store');
        Route::get('/ajaxUsers/{id}', 'getUsersAjax')->name('contact.ajax');
    });

    //projects
    Route::resource('project',ProjectController::class);
        Route::controller(ProjectController::class)->prefix('projects')->group(function () {
        Route::get('/ajaxSingleProject/{id}', 'getSingleAjax')->name('project.ajax');
        Route::get('/assign-user-project/{id}', 'assignUserToProjectForm')->name('project.assign.user.create');
        Route::post('/assign-user-project', 'assignUserToProject')->name('project.assign.user.store');
    });

    //timelines
    Route::resource('timeline',TimelineController::class);
    Route::get('/timelines/ajaxSingleTimeline/{id}', [TimelineController::class, 'getSingleAjax'])->name('timeline.ajax');

    //budgets
    Route::resource('budget',BudgetController::class);

    //contacttypes
    Route::resource('contacttype',ContacttypeController::class);

    //records
    Route::resource('record',RecordController::class);

    
    //Reports 
    Route::controller(ReportController::class)->prefix('reports')->group(function () {
        Route::get('/', 'index')->name('report.index');
        Route::get('/recordDetail', 'recordDetailForm')->name('report.recordDetailCreate');
        Route::post('/recordDetail','recordDetail')->name('report.recordDetailShow');
        Route::get('/ageing-report','ageingReportForm')->name('report.ageingReportCreate');
        Route::post('/ageing-report','ageingReport')->name('report.ageingReport');




        Route::get('/contactPayableReceivable','contactPayableReceivableForm')->name('report.contactPayableReceivableCreate');
        Route::post('/contactPayableReceivable', 'contactPayableReceivable')->name('report.contactPayableReceivable');

    });
    //transactions
    Route::resource('transaction',TransactionController::class)->only(['create','show','destroy']);



    //Admin
    Route::controller(AdminController::class)->prefix('admin')->group(function(){
        Route::get('/imitate/{id}','imitateLogin')->name('admin.imitate');
        Route::get('/imitateLogout','imitateLogout')->name('admin.imitateLogout')->withoutMiddleware('auth');
    });
});




