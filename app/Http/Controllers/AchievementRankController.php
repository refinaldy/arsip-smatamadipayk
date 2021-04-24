<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AchievementRank;
use Illuminate\Support\Facades\Validator;

class AchievementRankController extends Controller
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
        return AchievementRank::all();
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
            'kategori_juara' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        $rank = strtolower(request('kategori_juara'));
        $rank = AchievementRank::where('rank', $rank)->get()->count();

        if ($rank > 0) {
            $messages = [[
                'status' => "FAILED",
                'pesan' => "Data kategori prestasi salah atau sudah tersedia",
                'data' => [
                    'kategori_juara' => request('kategori_juara')
                ]
            ]];
            return response()->json($messages, 413);
        }

        $slug = 'prestasi-' . Str::slug(strtolower(request('kategori_juara')), '-');
        AchievementRank::create(
            [
                'rank' => strtolower(request('kategori_juara')),
                'slug' => $slug
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Kategori juara  berhasil ditambahkan",
            'data' => [
                'kategori_juara' => request('kategori_juara')
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
    public function show($attr)
    {

        if (is_numeric($attr)) {
            $achievementRank = AchievementRank::find($attr)->with('achievements')->get();
        } else {
            $achievementRank = AchievementRank::where('slug', $attr)->with('achievements')->get();
        }

        if ($achievementRank != null) {
            return $achievementRank;
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
            'kategori_juara' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        $slug = 'prestasi-' . Str::slug(strtolower(request('kategori_juara')), '-');
        AchievementRank::find($id)->update(
            [
                'rank' => strtolower(request('kategori_juara'))
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Kategori juara  berhasil diubah",
            'data' => [
                'kategori_juara' => request('kategori_juara')
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

        $achievementRank = AchievementRank::find($id);

        if ($achievementRank != null) {
            $achievementRank->delete();
            return response()->json(['messages' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }
}
