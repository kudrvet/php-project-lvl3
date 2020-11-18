<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
    return view('homepage');
})->name('homepage');

Route::post('/', function (Request $request) {
    $validationData = $request->validate(['domain.name' => 'url']);

    $name = $request->input('domain')['name'];
    $nowTime = Carbon::now()->toDateTimeString();

    $urlParts = parse_url($name);
    $normalizedName="{$urlParts['scheme']}://{$urlParts['host']}";

    DB::table('domains')->upsert(
        ['name' => $normalizedName, 'created_at' => $nowTime, 'updated_at' => $nowTime],
        'name',['updated_at']
    );

    $id = DB::table('domains')->select('id')->where('name','=',$normalizedName)->get()->toArray()[0]->id;

    flash('Site Added!')->success();

    return redirect()->route('domains.show',['id'=> $id]);
})->name('domains.store');

Route::get('/domains/{id}', function ($id) {

    $domain = DB::table('domains')->where('id','=',$id)->get()->toArray();
    if(empty($domain)) {
        abort(404);
    }

    return view('domains_show',['domain' => $domain[0]]);
})->name('domains.show');

Route::get('/domains', function () {

    $domains = DB::table('domains')->orderBy('id')->get();

    return view('domains_index',['domains' => $domains]);

})->name('domains.index');

