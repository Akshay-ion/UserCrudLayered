<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('user', UserController::class);

Route::get('/', function(){
    return view('index');
});

Route::get('insert', function(){

    $users = [];

    $count = 0;
    for($i = 0; $i < 1000; $i++){
        // $name = Illuminate\Support\Str::random(8);
        $count += 102;
        $name = "Akshay" . $count;

        $users[] = [
            'name' => $name,
            'email' => $name . '@gmail.com',
        ];
    }

    App\Models\User::insert($users);

    return "1000 users inserted successfully";
});
