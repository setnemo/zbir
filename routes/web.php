<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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
    return \response('Access denied', 401);
});

Route::get('/deploy', static function () {
    return response(
        null,
        file_exists(base_path() . '/deploy.pid') ?
            Response::HTTP_CONFLICT :
            Response::HTTP_NO_CONTENT
    );
})->middleware(['dev'])->name('dev_deploy');

Route::group(['middleware' => ['web']], static function () {
    $secret = Config::get('app.secret_start');
    Route::get('/' . $secret, static function () use ($secret) {
        if ('used' === session()->get('secret_start_key')) {
            return redirect(\route('apply'), Response::HTTP_FOUND);
        }
        session()->put('secret_start_key', $secret);
        return redirect(\route('apply'), Response::HTTP_FOUND);
    })->name('start');
    Route::get('/apply', static function () use ($secret) {
        if (session()->get('secret_start_key') !== $secret) {
            return \response('Access denied', Response::HTTP_UNAUTHORIZED);
        }
        session()->put('secret_start_key', 'used');
        return view('apply');
    })->name('apply');
    Route::post('/save', static function (Request $request) use ($secret) {
        $client = new Google_Client();
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(__DIR__ . '/../google.json');
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = Config::get('app.spreadsheet_id');
        $range = Config::get('app.spreadsheet_range');
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        $values[] = [$request->get('contact')];
        $service->spreadsheets_values->update(
            $spreadsheetId,
            $range,
            new Google_Service_Sheets_ValueRange(['values' => $values,]), ['valueInputOption' => 'RAW',]
        );
        session()->regenerate();

        return \response(['number' => count($values)], Response::HTTP_ACCEPTED);
    })->name('start');
});
