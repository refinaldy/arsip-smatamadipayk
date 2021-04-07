<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AchievementCategory;
use Illuminate\Support\Facades\Validator;

class AchievementCategoryController extends Controller
{

    public function __construct()
    {
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AchievementCategory::with('achievements')->get();
    }

    public function allCategory($slug)
    {
        $achievementCategory = AchievementCategory::where('slug', $slug)->with('achievements')->get();

        if ($achievementCategory != null) {
            return $achievementCategory;
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [];

        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        // validasi request
        $validator = Validator::make(request()->all(), [
            'kategori_prestasi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        $category = strtolower(request('kategori_prestasi'));
        $category = AchievementCategory::where('category', $category)->get()->count();

        if ($category > 0) {
            $messages = [[
                'status' => "FAILED",
                'pesan' => "Data kategori prestasi salah atau sudah tersedia",
                'data' => [
                    'kategori_prestasi' => request('kategori_prestasi')
                ]
            ]];
            return response()->json($messages, 413);
        }

        $slug = 'prestasi-' . Str::slug(strtolower(request('kategori_prestasi')), '-');
        AchievementCategory::create(
            [
                'category' => strtolower(request('kategori_prestasi')),
                'slug' => $slug
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Kategori prestasi berhasil ditambahkan",
            'data' => [
                'kategori_prestasi' => request('kategori_prestasi')
            ]
        ]];

        return response()->json($messages, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $achievementCategory = AchievementCategory::find($id);

        if ($achievementCategory != null) {
            return $achievementCategory;
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [];

        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        // validasi request
        $validator = Validator::make(request()->all(), [
            'kategori_prestasi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        AchievementCategory::find($id)->update(
            [
                'category' => strtolower(request('kategori_prestasi')),
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Kategori prestasi berhasil diubah",
            'data' => [
                'kategori_prestasi' => request('kategori_prestasi')
            ]
        ]];

        return response()->json($messages, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //check autentikasi
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        $achievementCategory = AchievementCategory::find($id);

        if ($achievementCategory != null) {
            $achievementCategory->delete();
            return response()->json(['messages' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }
}
