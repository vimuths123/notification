<?php

use App\Events\NotificationCreated;
use App\Events\OrderStatusUpdate;
use App\Events\TaskCreated;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpParser\Builder\Class_;

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


class Order
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

Route::get('/', function () {

    event(new OrderStatusUpdate(new Order(1)));



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

Route::get('/tasks', function () {
    return Task::latest()->pluck('body');
});

Route::post('/tasks', function () {
    $task = Task::forceCreate(request(['body']));
    event(
        (new TaskCreated($task))->dontBroadcastToCurrentUser()
    );
});

Route::get('/taskpage', function () {
    return Inertia::render('Tasklist');
});


Route::get('/projects/{project}', function (Project $project) {
    return Inertia::render('ProjectTasklist', [
        'project' => $project
    ]);
});

Route::get('/projects/{project}/tasks', function (Project $project) {
    return $project->tasks->pluck('body');
});

Route::post('/projects/{project}/tasks', function (Project $project) {
    $task = $project->tasks()->create(request(['body']));
    event(
        (new TaskCreated($task))->dontBroadcastToCurrentUser()
    );
});
