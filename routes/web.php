<?php

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

include_once __DIR__ .'/defined.php';
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $data = [];
    $data['themes'] = @json_decode(file_get_contents(asset('js/themes.json')));
    $data['fonts'] = @json_decode(file_get_contents(asset('js/fonts.json')));
    $data['timezones'] = @json_decode(file_get_contents(asset('js/timezones.json')));
    return view('index', $data);
});


//User defined routes
Route::post('routes/contents', 'RoutesController@getContents')->name('routes.contents');
Route::post('routes/update', 'RoutesController@updateContents')->name('routes.update');
Route::post('routes/check-errors', 'RoutesController@analyzeRoutesBeforeSave')->name('routes.check-errors');

//Updates
Route::get('check-updates', 'UpdatesController@checkUpdates');
Route::post('autocheck-updates', 'UpdatesController@autoCheckUpdates');
Route::get('download-updates', 'UpdatesController@downloadUpdates');
Route::post('do-updates', 'UpdatesController@doUpdates');
Route::post('clear-updates-session', 'UpdatesController@clearUpdateSession');
Route::get('autocheck', 'UpdatesController@doUpdates');

// Open terminal
Route::get('console/{what?}', function($option = '') {
    set_time_limit(0);

    if ($option != '') {
        shell_exec('start cmd.exe');
    }
    return view('console');
});


Route::get('/{vue_capture?}', function () {
    return redirect('/');
})->where('vue_capture', '[\/\w\.-]*');

Route::post('get-preview', 'EvaluationController@evaluate')->name('evaluate');
Route::post('change-theme', 'SettingsController@changeTheme')->name('settings.change.theme');
Route::post('change-theme-type', 'SettingsController@changeThemeType')->name('settings.change.theme.type');
Route::post('keep-last-codes', 'SettingsController@keepLastCodes')->name('settings.keep.last.codes');

Route::post('codelogs-update', 'CodelogsController@update')->name('codelogs.update');
Route::post('get-codes', 'CodelogsController@getCodes')->name('codelogs.get');

Route::post('snippets/add', 'SnippetsController@store')->name('snippets.store');
Route::post('snippets/show', 'SnippetsController@show')->name('snippets.show');
Route::post('snippets/update', 'SnippetsController@update')->name('snippets.update');
Route::post('/snippets/delete', 'SnippetsController@destroy')->name('snippets.delete');
Route::post('snippets/update-name', 'SnippetsController@updateName')->name('snippets.update.name');
Route::post('snippets/load-in-editor', 'SnippetsController@loadInEditor')->name('snippets.load-in-editor');

Route::post('/check-updates', 'SettingsController@checkUpdates')->name('settings.update.check');
Route::post('updates-active', 'SettingsController@checkIfUpdatesIsActive');
Route::post('settings/update-font', 'SettingsController@updateSystemFontFamily');
Route::post('settings/update-font-size', 'SettingsController@updateSystemFontSize');
Route::post('settings/get-dynamic', 'SettingsController@getDynamic');
Route::post('auto-update-checker', 'SettingsController@runAutoUpdateChecker');
Route::post('settings/postpone-update-check', 'SettingsController@postponeUpdateCheck');
Route::post('settings/update-timezone', 'SettingsController@updateTimezone');



