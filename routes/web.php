<?php

use App\Models\Expert;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
    Role::create([
        'role_name' => 'کاربر سازمان'
    ]);
    Role::create([
        'role_name' => 'کاربر استان'
    ]);
    Role::create([
        'role_name' => 'کاربر پیمانکار'
    ]);
    Role::create([
        'role_name' => 'ناظر ستاد'
    ]);

    Expert::create([
        'name_expert' => 'مهندسی ترابری'
    ]);
    Expert::create([
        'name_expert' => 'مهندسی تونل'
    ]);
    Expert::create([
        'name_expert' => 'مهندسی ترافیک'
    ]);
    Expert::create([
        'name_expert' => 'مهندسی حمل و نقل و ترافیک'
    ]);
    Expert::create([
        'name_expert' => 'برنامه ریزی حمل و نقل'
    ]);
    Expert::create([
        'name_expert' => 'روسازی راه (Pavement) '
    ]);


    User::create([
        'name' => 'jafar',
        'email' => 'a@a.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 1
    ]);
    User::create([
        'name' => 'mojtaba',
        'email' => 'b@b.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 2
    ]);
    User::create([
        'name' => 'sina',
        'email' => 'c@c.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 3
    ]);
    User::create([
        'name' => 'yoones',
        'email' => 'd@d.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role_id' => 4
    ]);

    return 'ok';
});
