<?php

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/send_notifications', function () {
        $users = User::all();
        return Inertia::render('SendNotification', [
            'users' => $users
        ]);
    });

    Route::post('/send_notifications', function (Request $request) {
        $notification = Notification::create([
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'body' => $request->input('body')
        ]);

        event(new NotificationCreated($notification));

        return redirect()->back()->banner('Notification added.');
    })->name('send_notifications');

    Route::get('get_notifications', function (Request $request) {
        return $user_notifications = Notification::where('user_id', $request->user()->id)
            ->where('read', false)
            ->latest()
            ->get();
    })->name('get_notifications');

    Route::get('click_notification/{notification}', function (Notification $notification) {
        $notification->read = true;
        $notification->save();
        
        return redirect()->back()->banner('Notification clicked.');
    })->name('click_notification');
});


