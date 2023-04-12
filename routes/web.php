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

//Route::get('/', [HomeController::class,'index']);
Route::get('/', [ProcessController::class,'index']);
//Route::post('/home', [HomeController::class,'index'])->name('login');
// マニュアル
Route::get('/home', [HomeController::class,'index']);

// 工程登録
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
Route::post('/process/update', [ProcessController::class,'updateProcessDetails']);
Route::post('/process/datacapture', [ProcessController::class,'updateDataCapture']);
Route::post('/process/updates', [ProcessController::class,'fix']);
Route::post('/process/insert', [ProcessController::class,'insertData']);
Route::post('/process/search', [ProcessController::class,'postSearch']);
Route::post('/process/wdget', [ProcessController::class,'workDate']);   // 部署の作業日取得
Route::any('/process/{getpost}', function ($getpost) {
    // {}がワイルドカード　なんでも入る。　$getpost 引数を設定して変数として使える
    return redirect('/process');
});


// 閲覧
Route::get('/view', [ViewController::class,'index']);
Route::post('/view', [ViewController::class,'index']);
Route::post('/view/get', [ViewController::class,'getData']);
Route::post('/view/search', [ViewController::class,'postSearch']);
Route::get('/view/search', [ViewController::class,'index']);

// ログ
Route::post('/log/search', [LogController::class,'postSearch']);





// 作業詳細
Route::get('/work', [WorkViewController::class,'index']);
Route::post('/work', [WorkViewController::class,'index']);
Route::get('/work/day', [WorkViewController::class,'daySearch']);
Route::get('/work/one', [WorkViewController::class,'oneSearch']);
Route::post('/work/get', [WorkViewController::class,'getData']);
Route::post('/work/workget', [WorkViewController::class,'getWORK']);
Route::post('/work/sttsup', [WorkViewController::class,'changeStatus']);
Route::post('/work/update', [WorkViewController::class,'fix']);
Route::post('/work/insert', [WorkViewController::class,'insertData']);
Route::post('/work/search', [WorkViewController::class,'postSearch']);
Route::post('/work/wdget', [WorkViewController::class,'workDate']);   // 部署の作業日取得



// 詳細編集
Route::get('/spec', [SpecController::class,'index']);
Route::post('/spec', [SpecController::class,'index']);
Route::post('/spec/get', [SpecController::class,'getData']);
Route::post('/spec/workget', [SpecController::class,'getWORK']);
Route::post('/spec/getone', [SpecController::class,'getDataOne']);
Route::post('/spec/update', [SpecController::class,'fix']);
Route::post('/spec/insert', [SpecController::class,'insertData']);
Route::post('/spec/search', [SpecController::class,'postSearch']);
Route::post('/spec/wdget', [SpecController::class,'workDate']);   // 部署の作業日取得









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
