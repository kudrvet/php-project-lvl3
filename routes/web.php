<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

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

    if ($validator->fails()) {
        flash('Invalid URL!')->error();
        return redirect()->route('homepage')->withInput()->withErrors($validator);
    }

    $name = $request->input('domain')['name'];
    $normalizedName = normalizeUrl($name);

    $nowTime = Carbon::now('Europe/Moscow')->toDateTimeString();

    $domainFromDB = DB::table('domains')->select()
        ->where('name', '=', $normalizedName)
        ->get()->first();

    if (empty($domainFromDB)) {
        $id = DB::table('domains')->insertGetId(
            ['name' => $normalizedName, 'created_at' => $nowTime, 'updated_at' => $nowTime]
        );

        flash('This url is added!')->success();
        return redirect()->route('domains.show', ['id' => $id]);
    }

    flash('This url is existed!')->success();
    return redirect()->route('domains.show', ['id' => $domainFromDB->id]);
})->name('domains.store');

Route::get('/domains/{id}', function ($id) {


    $domain = DB::table('domains')->find($id);
    if (empty($domain)) {
        abort(404);
    }

    $domainsChecks = DB::table('domain_checks')
        ->where('domain_id', '=', $id)->orderByDesc('created_at')->get();

    return view('domains_show', ['domain' => $domain, 'domainsChecks' => $domainsChecks]);
})->name('domains.show');

Route::get('/domains', function () {

    $lastChecksWithStatus = DB::table('domain_checks')
        ->select('domain_id', 'status_code', DB::raw('MAX(created_at) as last_post_created_at'))
        ->groupBy('domain_id', 'status_code');

    $domainsWithLastCheck = DB::table('domains')
        ->leftjoinSub($lastChecksWithStatus, 'latest_checks', function ($join) {
            $join->on('domains.id', '=', 'latest_checks.domain_id');
        })
        ->select('domains.id', 'domains.name', 'latest_checks.status_code', 'latest_checks.last_post_created_at')
        ->get();

    return view('domains_index', ['domains' => $domainsWithLastCheck]);
})->name('domains.index');

Route::post('/domains/{id}/checks', function ($id) {

    $nowTime = Carbon::now('Europe/Moscow')->toDateTimeString();

    try {
        $domainName = DB::table('domains')
         ->find($id, ['name'])->name;
        $response = Http::get($domainName);
        $status_code = $response->status();
        $parsedHtml = new DiDom\Document($response->body());
        $h1Tags = $parsedHtml->find('h1');
        $formattedH1Tags = array_map(function ($tag) {
            return $tag->text();
        }, $h1Tags);

//        $h1Tags = $parsedHtml->first('h1')->text();
//        dd($h1Tags);

        $keywords = optional($parsedHtml->first('[name="keywords"]'))->getAttribute('content');
        $description = optional($parsedHtml->first('[name="description"]'))->getAttribute('content');

        DB::table('domain_checks')
         ->insert(['domain_id' => $id,
             'status_code' => $status_code,
             'h1' => implode("", $formattedH1Tags),
             'keywords' => $keywords,
             'description' => $description,
             'created_at' => $nowTime, 'updated_at' => $nowTime]);
    } catch (Exception $e) {
        flash('Something go wrong!')->error();
    }

    return redirect(route('domains.show', ['id' => $id]));
})->name('domains.check');
