<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimelineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ContacttypeController;
use App\Http\Controllers\ContactController;
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

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashbaord');






Route::resources(['roles' => RoleController::class]);

Route::get('/assign-role', [RoleController::class, 'assign'])->name('role.assign');
Route::get('/edit-assigned-role', [RoleController::class, 'editAddAssignedRole'])->name('role.editAssign');
Route::post('/edit-assigned-role', [RoleController::class, 'updateAssignedRoles'])->name('role.updateAssign');


//users
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('signup');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/user-create', [UserController::class, 'create'])->name('user.create');
Route::post('/users', [UserController::class, 'register'])->name('user.register');
Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('/', 'index')->name('user.index');
    Route::get('/{id}', 'show')->name('user.show');
    Route::put('/{id}', 'update')->name('user.update');
    Route::get('/{id}/edit', 'edit')->name('user.edit');
    Route::delete('/{id}', 'destroy')->name('user.destroy');
});


//projects

Route::controller(ProjectController::class)->prefix('projects')->group(function () {
    Route::get('/', 'index')->name('project.index');
    Route::get('/create', 'createProject')->name('project.create');
    Route::post('/', 'store')->name('project.store');
    Route::get('/{id}', 'show')->name('project.show');
    Route::put('/{id}', 'update')->name('project.update');
    Route::delete('/{id}', 'destroy')->name('project.destroy');
    Route::get('/{id}/edit', 'edit')->name('project.edit');
    Route::get('/ajaxSingleProject/{id}', 'getSingleAjax')->name('project.ajax');
});


//projects timeline
Route::controller(TimelineController::class)->prefix('timelines')->group(function () {
    // Route::get('/', 'index');

    Route::get('/create', 'createTimeline')->name('timeline.create');
    Route::post('/', 'store')->name('timeline.store');
    // Route::get('/{id}', 'show');
    Route::get('/{id}/edit', 'edit')->name('timeline.edit');
    Route::put('/{id}', 'update')->name('timeline.update');
    Route::delete('/{id}', 'destroy')->name('timeline.destroy');
    Route::get('/ajaxSingleTimeline/{id}', 'getSingleAjax')->name('timeline.ajax');
});


//projects budget
Route::controller(BudgetController::class)->prefix('budgets')->group(function () {
    Route::get('/', 'index');
    Route::post('/','store')->name('budget.store');
    Route::get('/create', 'createBudget')->name('budget.create');
    Route::get('/{id}', 'show');
    Route::get('/{id}/edit', 'edit')->name('budget.edit');
    Route::put('/{id}', 'update')->name('budget.update');
    Route::delete('/{id}', 'destroy')->name('budget.destroy');
});

//many to many relation controller
Route::controller(RelationController::class)->group(function () {
    Route::post('/assign-project-user', 'assignProjectUser')->name('assign.project.user');
    Route::get('/assign-project-user/{id}', 'assignProjectUserForm')->name('assign.project.create');
    Route::post('/assign-user-project','assignUserProject')->name('assign.user.project');
    Route::get('/assign-user-project/{id}', 'assignUserProjectForm')->name('assign.user.create');
    Route::post('/dettach-user-project', 'detachProjectUser');
});

//contact types
Route::controller(ContacttypeController::class)->prefix('contacttypes')->group(function () {
    Route::get('/', 'index')->name('contacttype.index');
    Route::get('/create', 'create')->name('contacttype.create');
    Route::post('/', 'store')->name('contacttype.store');
    Route::get('/{id}/edit', 'edit')->name('contacttype.edit');
    Route::get('/{id}', 'show')->name('contacttype.show');
    Route::put('/{id}', 'update')->name('contacttype.update');
    Route::delete('/{id}', 'destroy')->name('contacttype.destroy');
});


//contacts
Route::controller(ContactController::class)->group(function () {
    Route::get('/create-contact/{id}','create')->name('contact.create');
    Route::post('/create-contact', 'createContact')->name('contact.store');
    Route::get('/contact/ajaxSingleUsers/{id}', 'getSingleAjax')->name('contact.ajax');
});


//transactions
Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
    Route::get('/', 'index')->name('transaction.index');
    Route::get('/create','create')->name('transaction.create');
    Route::post('/', 'store')->name('transaction.store');
    Route::get('/{id}', 'show')->name('transaction.show');
    Route::put('/{id}', 'update')->name('transaction.update');
    Route::delete('/{id}', 'destroy')->name('transaction.destroy');
});
