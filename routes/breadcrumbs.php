<?php
use App\Models\Record;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
//     $trail->push('Home', route('dashboard'));
// });
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('user.index'));
});

Breadcrumbs::for('user.create', function (BreadcrumbTrail $trail) {
    $trail->parent('user.index');
    $trail->push('New User', route('user.create'));
});
Breadcrumbs::for('record.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Records', route('record.index'));
});

Breadcrumbs::for('record.show', function (BreadcrumbTrail $trail,Record $record) {
    $trail->parent('record.index');
    $trail->push($record->code, route('record.show',$record));
});

Breadcrumbs::for('record.create', function (BreadcrumbTrail $trail) {
    $trail->parent('record.index');
    $trail->push('Create New', route('record.create'));
});

Breadcrumbs::for('record.edit', function (BreadcrumbTrail $trail, Record $record) {
    $trail->parent('record.index');
    $trail->push('Edit Record - '.$record->code, route('record.edit', $record));
});