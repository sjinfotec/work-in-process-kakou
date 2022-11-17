<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\Authenticate;


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

/*
Route::get('/', function () {
    return view('welcome');
});
*/
/*
Route::get('/', function () {
    return view('contents');
});
*/
/*Route::get('/', 'HomeController@index')->name('home');*/
//Auth::routes();
//ログインが必要な場合は、 ->middleware('auth') を追加する
//Route::get('/', [HomeController::class,'index'])->middleware('auth');

Route::get('/', [HomeController::class,'index']);
//Route::post('/home', [HomeController::class,'index'])->name('login');
Route::get('/home', [HomeController::class,'index']);
Route::get('/regi', [RegisterController::class,'getRequestFunc']);
Route::post('/regi', [RegisterController::class,'postRequestFunc']);
Route::post('/regi/search', [RegisterController::class,'searchData']);
Route::post('/regi/new', [RegisterController::class,'newData']);

// 工程編集
Route::get('/process', [ProcessController::class,'index']);
Route::post('/process', [ProcessController::class,'index']);
Route::post('/process/get', [ProcessController::class,'getData']);
Route::post('/process/workget', [ProcessController::class,'getWORK']);
Route::post('/process/getone', [ProcessController::class,'getDataOne']);
Route::post('/process/update', [ProcessController::class,'fix']);
Route::post('/process/insert', [ProcessController::class,'insertData']);
Route::post('/process/search', [ProcessController::class,'postSearch']);
Route::post('/process/wdget', [ProcessController::class,'workDate']);   // 部署の作業日取得







Route::get('/list', [HomeController::class,'getRequestFunc']);
Route::post('/list', [HomeController::class,'postRequestFunc']);
Route::post('/list/search', [HomeController::class,'searchData']);

Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login'); // view は auth.login
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// 見積作成
//Route::get('/m_make', [MmakeController::class,'index'])->middleware('auth');
Route::get('/m_make', [MmakeController::class,'topView'])->middleware('auth');
Route::post('/m_make/get', [MmakeController::class,'getDataA'])->middleware('auth');
Route::post('/m_make/getone', [MmakeController::class,'getDataAone'])->middleware('auth');
Route::post('/m_make/update', [MmakeController::class,'fixA'])->middleware('auth');
Route::post('/m_make/insert', [MmakeController::class,'storeA'])->middleware('auth');
Route::post('/m_make/search', [MmakeController::class,'getDataAsearch'])->middleware('auth');

// 見積作成
Route::get('/quotations', [QuotationsController::class,'index'])->middleware('auth');
Route::post('/quotations/get', [QuotationsController::class,'getData'])->middleware('auth');
Route::post('/quotations/getone', [QuotationsController::class,'getDataOne'])->middleware('auth');
Route::post('/quotations/update', [QuotationsController::class,'fix'])->middleware('auth');
Route::post('/quotations/insert', [QuotationsController::class,'store'])->middleware('auth');
Route::get('/quotations/binding', [QuotationsBindingController::class,'index'])->middleware('auth');
Route::get('/quotations/cost', [QuotationsCostController::class,'index'])->middleware('auth');
Route::get('/quotations/department', [QuotationsDepartmentController::class,'index'])->middleware('auth');

//Route::get('/parts', [PartsController::class,'index'])->middleware('auth');
//Route::post('/parts/get', [PartsController::class,'getitem']);
//Route::post('/parts/get', 'PartsController@getitem');
Route::post('/parts/get', [PartsController::class,'getitem'])->middleware('auth');
Route::post('/outsourcing/get', [OutsourcingController::class,'getRequest'])->middleware('auth');

// 見積検索
Route::get('/qsearch', [QuotationsController::class,'search'])->middleware('auth');
Route::post('/qsearch/get', [QuotationsController::class,'getDataSearch'])->middleware('auth');



// 見積書
Route::get('/qdoc', [QuotationsDocController::class,'index'])->middleware('auth');

// 印刷
Route::get('/print_q', [QuotationsController::class,'index'])->middleware('auth');
Route::post('/print_q/get', [QuotationsController::class,'getSeaDetail'])->middleware('auth');



// 管理
Route::get('/maintenance/backup', [BackupLogsController::class,'index'])->middleware('auth');
