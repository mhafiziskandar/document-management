<?php

use App\Models\Folder;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('admin.projects.index', function (BreadcrumbTrail $trail) {
    $trail->push('MyProjek', route('admin.projects.index'));
});

Breadcrumbs::for('admin.projects.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.projects.index');
    $trail->push('Tambah Projek', route('admin.projects.create'));
});

Breadcrumbs::for('admin.projects.edit', function (BreadcrumbTrail $trail, Folder $folder) {
    $trail->parent('admin.projects.index');
    $trail->push('Kemas Kini Projek ' . $folder->project_name, route('admin.projects.edit', $folder));
});

Breadcrumbs::for('admin.projects.show', function (BreadcrumbTrail $trail, Folder $folder) {
    $trail->parent('admin.projects.index');
    $trail->push('MyProjek : ' . $folder->project_name, route('admin.projects.show', $folder));
});

Breadcrumbs::for('admin.files.index', function (BreadcrumbTrail $trail) {
    $trail->push('File', route('admin.files.index'));
});

Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail) {
    $trail->push('User', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.edit', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push('Edit User', route('admin.users.edit', $user));
});

Breadcrumbs::for('admin.bins.index', function (BreadcrumbTrail $trail) {
    $trail->push('Bin Management', route('admin.bins.index'));
});

Breadcrumbs::for('admin.bins.project', function (BreadcrumbTrail $trail) {
    $trail->push('Bin Project Management', route('admin.bins.project'));
});

Breadcrumbs::for('admin.settings.clusters.index', function (BreadcrumbTrail $trail) {
    $trail->push('Cluster Management', route('admin.settings.clusters.index'));
});

Breadcrumbs::for('admin.settings.docTypes.index', function (BreadcrumbTrail $trail) {
    $trail->push('Folder Type Management', route('admin.settings.docTypes.index'));
});

Breadcrumbs::for('member.projects.index', function (BreadcrumbTrail $trail) {
    $trail->push('MyProjek', route('member.projects.index'));
});

Breadcrumbs::for('member.projects.show', function (BreadcrumbTrail $trail, Folder $folder) {
    $trail->parent('member.projects.index');
    $trail->push('Projek : ' . $folder->project_name, route('member.projects.show', $folder));
});

Breadcrumbs::for('member.files.index', function (BreadcrumbTrail $trail) {
    $trail->push('File', route('member.files.index'));
});

Breadcrumbs::for('projects.public', function (BreadcrumbTrail $trail) {
    $trail->push('Projek Umum', route('projects.public'));
});

Breadcrumbs::for('projects.public.show', function (BreadcrumbTrail $trail, Folder $folder) {
    $trail->parent('projects.public');
    $trail->push('Projek : '. $folder->project_name, route('projects.public.show', $folder));
});

Breadcrumbs::for('admin.account-settings', function (BreadcrumbTrail $trail) {
    $trail->push('Tukar Email/Password', route('admin.account-settings'));
});

Breadcrumbs::for('member.account-settings', function (BreadcrumbTrail $trail) {
    $trail->push('Tukar Email/Password', route('member.account-settings'));
});
