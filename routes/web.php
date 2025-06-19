<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ObwisController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DetailEventController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


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


/*
Route::get('/', function () {
    return view('login');
});
*/

/* Rute untuk halaman utama (Home) mengarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login'); // Mengarahkan ke halaman login
})->name('home');
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Rute untuk dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index'); // Rute umum untuk dashboard
});

// Rute untuk registrasi
Route::get('/register', [AuthController::class, 'registration'])->name('register');
Route::post('/register', [AuthController::class, 'customRegistration'])->name('register.custom');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'signOut'])->name('logout');

// Route untuk halaman reset password
Route::get('/password-reset', [AuthController::class, 'showPasswordResetForm'])->name('password.reset');
Route::post('/password-reset', [AuthController::class, 'updatePassword'])->name('password.update');

// Rute untuk halaman pengaturan pengguna
Route::middleware('auth')->group(function () {
    Route::get('/settings', [AuthController::class, 'showSettings'])->name('auth.settings');
    Route::post('/settings', [AuthController::class, 'updateSettings'])->name('auth.settings.update');
});

// Rute untuk ActivityLogController
Route::prefix('activity')->middleware('auth', 'user-access:admin')->group(function () {
    Route::get('/log', [ActivityLogController::class, 'activityLog'])->name('activity.log');
    Route::delete('activity-log/delete', [ActivityLogController::class, 'destroy'])->name('activity.log.destroy');
});

// Event Routes
Route::resource('kegiatan', EventController::class);

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('event.index'); // Untuk menampilkan halaman kalender
    Route::delete('{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('{id}', [EventController::class, 'update'])->name('events.update');
    Route::get('for-date', [EventController::class, 'getEventsForDate'])->name('events.forDate');
    Route::post('{event_id}/add-note', [EventController::class, 'addNote'])->name('events.addNote');
});

// Rute untuk detail event
Route::prefix('events/{event_id}/detailevent')->middleware('auth', 'user-access:admin,staff')->group(function () {
    Route::get('/', [DetailEventController::class, 'index'])->name('events.detailevent.index'); // Menampilkan daftar detail event
    Route::get('/create', [DetailEventController::class, 'create'])->name('events.detailevent.create'); // Menampilkan form untuk menambah detail event
    Route::post('/', [DetailEventController::class, 'store'])->name('events.detailevent.store'); // Menyimpan detail event baru
    Route::get('/{id}/edit', [DetailEventController::class, 'edit'])->name('events.detailevent.edit'); // Menampilkan form untuk mengedit detail event
    Route::patch('/{id}', [DetailEventController::class, 'update'])->name('events.detailevent.update'); // Memperbarui detail event
    Route::delete('/{id}', [DetailEventController::class, 'destroy'])->name('events.detailevent.destroy'); // Menghapus detail event
});

// Obwis Routes
Route::resource('obwis', ObwisController::class);
Route::prefix('obwis')->group(function () {
    Route::get('/', [ObwisController::class, 'index'])->name('obwis.index'); // Untuk menampilkan halaman kalender
    Route::delete('{id}', [ObwisController::class, 'destroy'])->name('obwis.destroy');
    Route::get('{id}/edit', [ObwisController::class, 'edit'])->name('obwis.edit');
    Route::patch('{id}', [ObwisController::class, 'update'])->name('obwis.update');
    Route::post('update-status/{id}', [ObwisController::class, 'updateStatus'])->name('obwis.updateStatus');
});

// Calendar Routes
Route::prefix('calendar')->group(function () {
    Route::get('/', [CalendarController::class, 'index'])->name('calendar.index'); // Untuk menampilkan halaman kalender
    Route::post('ajax', [CalendarController::class, 'ajax'])->name('calendar.ajax'); // Untuk menambah, update, dan hapus event
    Route::get('data', [CalendarController::class, 'calendarData'])->name('calendar.data'); // Untuk mengambil data event dalam format JSON
    Route::get('full-calender', [CalendarController::class, 'calendarData'])->name('calendar.full-calender');
    Route::get('events-for-date', [CalendarController::class, 'getEventsForDate'])->name('calendar.events-for-date');
    Route::get('events/{event_id}/edit', [CalendarController::class, 'editEvent'])->name('calendar.events.edit');
    Route::post('notes/add', [CalendarController::class, 'addNoteByDate'])->name('calendar.notes.add');
});

// Notes Routes
Route::prefix('notes')->middleware('auth', 'user-access:admin,staff')->group(function () {
    Route::get('for-date', [NotesController::class, 'getNoteForDate']);
    Route::post('add', [NotesController::class, 'addNote']);
    Route::put('update/{id}', [NotesController::class, 'update']); // Route for updating a note
    Route::delete('delete/{id}', [NotesController::class, 'destroy']); // Route for deleting a note
});

// Event Report Routes
Route::prefix('events/report')->middleware('auth', 'user-access:admin,staff')->group(function () {
    Route::get('/', [EventController::class, 'showReport'])->name('kegiatan.report');
    Route::post('generate', [EventController::class, 'generateReport'])->name('events.report.generate');
    Route::post('pdf', [PdfController::class, 'eventPdf'])->name('events.report.pdf');
});

// Routes untuk mencetak laporan objek wisata (obwis)
Route::prefix('report')->group(function () {
    Route::get('/', [ObwisController::class, 'showReport'])->name('obwis.report'); // Menampilkan laporan objek wisata
    Route::post('generate', [ObwisController::class, 'generateReport'])->name('obwis.report.generate'); // Menghasilkan laporan objek wisata
    Route::post('pdf', [PdfController::class, 'obwisPdf'])->name('obwis.report.pdf'); // Mengunduh laporan objek wisata dalam format PDF
});

// Routes untuk menampilkan laporan detail event
Route::prefix('events')->middleware('auth', 'user-access:admin,staff')->group(function () {
    Route::get('{event_id}/print-detail', [DetailEventController::class, 'DetailPrint'])->name('events.detailPrint'); // Menampilkan detail laporan event
    Route::get('{event}/print', [PdfController::class, 'printDetail'])->name('events.printDetail'); // Mengunduh detail laporan event dalam format PDF
});

// Rute untuk manajemen pengguna
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::resource('plan', PlanController::class);
// Rute untuk set selesai
Route::patch('plan/{id}/setSelesai', [PlanController::class, 'setSelesai'])->name('plan.setSelesai');
