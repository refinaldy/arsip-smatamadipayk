<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AcademicYearResource;
use App\Http\Resources\AcademicYearCollection;

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
        $academicYears = AcademicYearResource::collection(AcademicYear::withCount('students')->get());
        $tempMessages = 'Berhasil mendapatkan detail data tahun akademik ';
        $messages = $this->getSuccessMessages('SUCCESS', $tempMessages, 200);
        $messages['data'] = $academicYears;
        return $messages;
    }

    public function show($attr)
    {
        if (is_numeric($attr)) {
            $academicYear =  AcademicYearResource::make(AcademicYear::where('id', $attr)->with('students'))->first();
        } else {
            $year = explode('-', $attr);
            $yearStart = $year[0];
            $yearEnd = $year[1];
            $academicYear =  AcademicYearResource::make(AcademicYear::where('year_start', $yearStart)->where('year_end', $yearEnd)
                ->with('students')->first());
            if ($academicYear->resource === null) {
                return response()->json(['messages' => 'Data tidak ditemukan'], 404);
            }
        }


        if ($academicYear != null) {
            $tempMessages = 'Berhasil mendapatkan detail data tahun akademik';
            $messages = $this->getSuccessMessages('SUCCESS', $tempMessages, 200);
            $messages['data'] = $academicYear;
            return $messages;
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
            'tahun_awal' => 'required|max:4',
            'tahun_akhir' => 'required|max:4'
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
                    'tahun_awal' => request('tahun_awal'),
                    'tahun_akhir' => request('tahun_akhir')
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
                'tahun_awal' => request('tahun_awal'),
                'tahun_akhir' => request('tahun_akhir')
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

        return response()->json($messages, 201);
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

    private function getSuccessMessages($status, $messages, $httpCode)
    {
        return $messages = [
            'status' => $status,
            'pesan' => $messages,
            'kode' => $httpCode
        ];
    }

    public function countStudentByYear($yearStart, $yearEnd)
    {

        $year = AcademicYear::where('year_start', '=', $yearStart)
            ->where('year_end', '=', $yearEnd)->withCount('students')->get()->first();
        if ($year != null) {
            return response()->json([
                'status' => 'success',
                'kode' => '200',
                'pesan' => 'Data berhasil didapatkan',
                'data' => $year->students_count
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'kode' => '404',
                'pesan' => 'Tahun tidak ditemukan',
                'tahun' => $yearStart . '/' . $yearEnd
            ], 404);
        }
    }
}
