<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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

    $validator = Validator::make($request->input('domain'), [
        'name' => 'url']);

    if($validator->fails()) {
        flash('Invalid URL!')->error();
        return redirect()->route('homepage')->withInput();
    }

    $name = $request->input('domain')['name'];
    $normalizedName = normalizeUrl($name);

    $nowTime = Carbon::now('Europe/Moscow')->toDateTimeString();

    $domainFromDB = DB::table('domains')->select()
        ->where('name','=',$normalizedName)
        ->get()->toArray();

    if(empty($domainFromDB)) {
        $id = DB::table('domains')->insertGetId(
            ['name' => $normalizedName, 'created_at' => $nowTime, 'updated_at' => $nowTime]
        );

        flash('This url is added!')->success();
        return redirect()->route('domains.show',['id'=> $id]);
    }

    flash('This url is existed!')->success();
    return redirect()->route('domains.show',['id'=> $domainFromDB[0]->id]);

})->name('domains.store');

Route::get('/domains/{id}', function ($id) {

    $domain = DB::table('domains')->where('id','=',$id)->get()->all();

    if(empty($domain)) {
        abort(404);
    }

    $domainsChecks = DB::table('domain_checks')
        ->where('domain_id','=',$id)->orderByDesc('created_at')->get();

    return view('domains_show',['domain' => $domain[0], 'domainsChecks' => $domainsChecks]);
})->name('domains.show');

Route::get('/domains', function () {

    //бага - если не сделал ни одного чека, то выводится пустая таблица, а нужно с пустой строкой в ласт чек
    $latestChecks = DB::table('domain_checks')
        ->select('domain_id','status_code',DB::raw('MAX(created_at) as last_post_created_at'))
        ->groupBy('domain_id');

    $domainsWithLastCheck = DB::table('domains')
        ->leftjoinSub($latestChecks, 'latest_checks', function ($join) {
            $join->on('domains.id', '=', 'latest_checks.domain_id');
        })
        ->select('domains.id','domains.name','latest_checks.status_code','latest_checks.last_post_created_at')
        ->get();
//    dd($domainsWithLastCheck);
    return view('domains_index',['domains' => $domainsWithLastCheck]);

})->name('domains.index');

Route::post('/domains/{id}/checks', function ($id) {

    $nowTime = Carbon::now('Europe/Moscow')->toDateTimeString();
    DB::table('domain_checks')->insert(['domain_id' => $id,
        'created_at' => $nowTime, 'updated_at' => $nowTime]);

    return redirect(route('domains.show',['id' => $id]));
})->name('domains.check');