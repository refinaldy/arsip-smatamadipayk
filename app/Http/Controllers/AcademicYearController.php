<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicYearController extends Controller
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
        return AcademicYear::with('students')->get();
    }

    public function show($id)
    {
        $academicYear = AcademicYear::find($id)->with('students')->get();

        if ($academicYear != null) {
            return $academicYear;
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
            'tahun_awal' => 'required',
            'tahun_akhir' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        $year_start = AcademicYear::where('year_start', request('tahun_awal'))->get()->count();
        $year_end = AcademicYear::where('year_end', request('tahun_akhir'))->get()->count();

        if ($year_start > 0 || $year_end > 0) {
            $messages = [[
                'status' => "FAILED",
                'pesan' => "Data tahun ajaran salah atau sudah tersedia",
                'data' => [
                    'year_start' => request('tahun_awal'),
                    'year_end' => request('tahun_akhir')
                ]
            ]];

            return response()->json($messages, 413);
        }

        AcademicYear::create(
            [
                'year_start' => request('tahun_awal'),
                'year_end' => request('tahun_akhir')
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Data tahun ajaran berhasil disimpan",
            'data' => [
                'year_start' => request('tahun_awal'),
                'year_end' => request('tahun_akhir')
            ]
        ]];

        return response()->json($messages, 200);
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
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        // validasi request
        $validator = Validator::make(request()->all(), [
            'tahun_awal' => 'required',
            'tahun_akhir' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        AcademicYear::find($id)->update(
            [
                'year_start' => request('tahun_awal'),
                'year_end' => request('tahun_akhir')
            ]
        );

        $messages = [[
            'status' => "SUCCESS",
            'pesan' => "Data tahun ajaran berhasil diperbaruhi",
            'data' => [
                'year_start' => request('tahun_awal'),
                'year_end' => request('tahun_akhir')
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

        $academicYear = AcademicYear::find($id);

        if ($academicYear != null) {
            $academicYear->delete();
            return response()->json(['messages' => 'Data tahun ajaran berhasil dihapus'], 200);
        } else {
            return response()->json(['messages' => 'Data tahun ajaran tidak ditemukan'], 404);
        }
    }
}
