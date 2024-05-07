<?php

use App\Models\Record;
use App\Models\Project;
use App\Models\User;
use App\Models\Contacttype;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Database\Eloquent\Model;


Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

//resource try
Breadcrumbs::macro('resource', function (string $name, string $title, string $toShow) {
    Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
        $trail->parent('dashboard');
        $trail->push($title, route("{$name}.index"));
    });


    Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
        $trail->parent("{$name}.index");
        $trail->push('New', route("{$name}.create"));
    });


    Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, Model $model) use ($toShow, $name) {
        $trail->parent("{$name}.index");
        $trail->push($model->$toShow, route("{$name}.show", $model));
    });

    Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, Model $model) use ($name) {
        $trail->parent("{$name}.show", $model);
        $trail->push('Edit', route("{$name}.edit", $model));
    });
});

//contacttypes breadcrumb
Breadcrumbs::resource('contacttype', 'Contacttypes', 'name', 'Contacttype');

//user
Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('user.index'));
});

Breadcrumbs::for('user.create', function (BreadcrumbTrail $trail) {
    $trail->parent('user.index');
    $trail->push('New User', route('user.create'));
});

//record
Breadcrumbs::for('record.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Records', route('record.index'));
});

Breadcrumbs::for('record.show', function (BreadcrumbTrail $trail, Record $record) {
    $trail->parent('record.index');
    $trail->push($record->code, route('record.show', $record));
});

Breadcrumbs::for('record.create', function (BreadcrumbTrail $trail) {
    $trail->parent('record.index');
    $trail->push('Create New', route('record.create'));
});

Breadcrumbs::for('record.edit', function (BreadcrumbTrail $trail, Record $record) {
    $trail->parent('record.index');
    $trail->push('Edit Record - ' . $record->code, route('record.edit', $record));
});

//projects
Breadcrumbs::for('project.assign.user.create', function (BreadcrumbTrail $trail, Project $project) {
    $trail->parent('project.index');
    $trail->push('Assign Users to project', route('project.assign.user.create', $project));
});

Breadcrumbs::resource('project','Projects','title','Project');



//roles
Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Roles', route('roles.index'));
});

Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push('New Role', route('roles.create'));
});

Breadcrumbs::for('role.assign', function (BreadcrumbTrail $trail) {
    $trail->parent('roles.index');
    $trail->push('Assigned Roles', route('role.assign'));
});

Breadcrumbs::for('roles.editAssign', function (BreadcrumbTrail $trail,User $user) {
    $trail->parent('role.assign');
    $trail->push('Edit Assigned Roles',route('roles.editAssign',$user));
});






