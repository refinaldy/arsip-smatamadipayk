<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['status' => 'Unauthenticated, you have to login first', 'error' => 401]);
})->name('login-error');

Route::group(['middleware' => 'api'], function ($router) {

    Route::prefix('auth')->group(function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::post('register', 'RegisterController@register');
    });

    Route::apiResource('siswa', 'StudentController');
    Route::post('cari/siswa/', 'StudentController@search');
    Route::apiResource('tahun-akademik', 'AcademicYearController');

    Route::post('siswa/{id}/dokumen-kelulusan', 'StudentController@uploadGraduatedDocument');
    Route::get('siswa/{id}/dokumen-kelulusan/', 'StudentController@getGraduatedDocument');

    Route::apiResource('prestasi', 'AchievementController');
    Route::apiResource('kategori-juara', 'AchievementRankController');
    Route::get('kategori-juara/tag/{slug}', 'AchievementRankController@allRank'); // get all achievement by rank 

    Route::apiResource('kategori-prestasi', 'AchievementCategoryController');
    Route::get('kategori-prestasi/tag/{slug}', 'AchievementCategoryController@allCategory'); // get all achievement by category

    Route::get('kategori', 'AchievementController@getAllCategory');
    Route::get('jumlah/siswa/', 'StudentController@countStudent');
    Route::get('jumlah/prestasi', 'AchievementController@countAchievement');
});
