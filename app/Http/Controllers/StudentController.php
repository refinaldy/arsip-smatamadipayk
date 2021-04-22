<?php

namespace App\Http\Controllers;


use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\GraduatedDocument;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GraduatedDocumentResource;

class StudentController extends Controller
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
        $students = Student::with('academic_year')->get();

        return StudentResource::collection($students);
    }

    public function countStudent()
    {

        $data = ['jumlah_siswa' => Student::all()->count()];

        return response()->json([
            'status' => 'success',
            'kode' => '200',
            'pesan' => 'Data berhasil didapatkan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $messages = [];

        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        // validasi request
        $validator = Validator::make(request()->all(), $this->getValidationAttribute());

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }
        // nama lengkap, nisn, nis, tempat_lahir, tanggal_lahir, jenis_kelamin, slug, jurusan, tahun_lulus
        $imageName = request('foto_siswa');

        if (request('foto_siswa')) {
            $post_img = request('foto_siswa');
            $imageName = 'Foto-' . Str::slug(request('nama_lengkap'), '-') . '-' . request('nisn') . '.' . $post_img->extension();
            $post_img->move(public_path('images/student_images'), $imageName);
        }

        $slug = Str::slug(request('nama_lengkap'), '-') . '-' . request('nisn');
        $nisn = Student::where('nisn', request('nisn'))->get()->count();
        $nis = Student::where('nis', request('nis'))->get()->count();

        if ($nisn > 0) {
            $messages = $this->failedMessages("Data tidak berhasil disimpan, karena nisn sudah pernah dipakai", request());
            return response()->json($messages);
        } else if ($nis > 0) {
            $messages = $this->failedMessages("Data tidak berhasil disimpan, karena nis sudah pernah dipakai", request());
            return response()->json($messages);
        } else {

            Student::create(
                [
                    'full_name' => request('nama_lengkap'),
                    'nisn' => request('nisn'),
                    'nis' => request('nis'),
                    'birth_date' => request('tanggal_lahir'),
                    'birth_place' => request('tempat_lahir'),
                    'gender' => request('jenis_kelamin'),
                    'slug' => $slug,
                    'major' => request('jurusan'),
                    'academic_year_id' => request('id_tahun'),
                    'image' => $imageName
                ]
            );

            $messages = [[
                'status' => "SUCCESS",
                'pesan' => "Data alumni berhasil disimpan",
                'data' => [
                    'nama_lengkap' => request('nama_lengkap'),
                    'nisn' => request('nisn'),
                    'nis' => request('nis'),
                    'tanggal_lahir' => request('tanggal_lahir'),
                    'tempat_lahir' => request('tempat_lahir'),
                    'jenis_kelamin' => request('jenis_kelamin'),
                    'jurusan' => request('jurusan'),
                    'tahun_lulus' => request('id_tahun'),
                    'foto_siswa' => $imageName
                ]
            ]];
        }

        return response()->json($messages, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);

        if ($student != null) {
            return new StudentResource($student);
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
        //check autentikasi
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        // validasi request
        $validator = Validator::make(request()->all(), $this->getValidationAttribute());

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }
        // nama lengkap, nisn, nis, tempat_lahir, tanggal_lahir, jenis_kelamin, slug, jurusan, tahun_lulus
        $imageName = request('foto_siswa');

        if (request('foto_siswa')) {
            $post_img = request('foto_siswa');
            $imageName = 'foto-' . request('nama_lengkap') . '-' . request('nisn') . '.' . $post_img->extension();
            $post_img->move(public_path('images/student_images'), $imageName);
        }

        Student::find($id)->update(
            [
                'full_name' => request('nama_lengkap'),
                'nisn' => request('nisn'),
                'nis' => request('nis'),
                'birth_date' => request('tanggal_lahir'),
                'birth_place' => request('tempat_lahir'),
                'gender' => request('jenis_kelamin'),
                'major' => request('jurusan'),
                'academic_year_id' => request('id_tahun'),
                'image' => $imageName
            ]
        );

        $messages = [
            'status' => "SUCCESS",
            'pesan' => "Data alumni berhasil diubah",
            'data' => [
                'nama_lengkap' => request('nama_lengkap'),
                'nisn' => request('nisn'),
                'nis' => request('nis'),
                'tanggal_lahir' => request('tanggal_lahir'),
                'tempat_lahir' => request('tempat_lahir'),
                'jenis_kelamin' => request('jenis_kelamin'),
                'jurusan' => request('jurusan'),
                'id_tahun' => request('id_tahun'),
                'foto_siswa' => request('foto_siswa')
            ]
        ];

        return response()->json($messages, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //check autentikasisss  
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        $student = Student::find($id);

        if ($student != null) {
            $student->delete();
            return response()->json(['messages' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }

    public function getGraduatedDocument($id)
    {
        //check autentikasi
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }



        $student = new GraduatedDocumentResource(Student::find($id)->with('graduated_document')->first());

        if ($student != null) {
            return $student;
        } else {
            return response()->json(['messages' => 'Data tidak ditemukan'], 404);
        }
    }

    public function uploadGraduatedDocument($id, Request $request)
    {
        //check autentikasi
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('messages : Not Authorized', 403);
        }

        $student = Student::find($id);

        $validator = Validator::make(request()->all(), [
            'ijazah' => 'required|mimes:pdf|max:5120',
            'skhun' => 'required|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 417);
        }

        $academicYearId = $student->academic_year_id;
        $year = AcademicYear::find($academicYearId)->get()->first();
        $graduatedYear = $year->year_start . '-' . $year->year_end;

        if ($request->ijazah) {
            $ijazah = $request->ijazah;
            $fileIjazah = 'Ijazah-'  . $student->full_name . '-' . $student->nisn . '-' . $graduatedYear . '.' . $ijazah->extension();
            $ijazah->move(public_path('images/graduated_document'), $fileIjazah);
        }

        if ($request->skhun) {
            $skhun = $request->skhun;
            $fileSkhun =  'SKHUN-'  . $student->full_name . '-' . $student->nisn . '-' . $graduatedYear . '.' . $skhun->extension();
            $skhun->move(public_path('images/graduated_document'), $fileSkhun);
        }

        $document = $student->graduated_document()->create([
            'ijazah_file' => $fileIjazah,
            'skhun_file' => $fileSkhun,
            'status' => 'AVAILABLE',
        ]);



        if ($document) {
            $messages = [
                'status' => "SUCCESS",
                'pesan' => "File ijazah dan SKHUN berhasil diinputkan",
                'data' => [
                    'nama_lengkap' => $student->full_name,
                    'nisn' => $student->nisn,
                    'ijazah' => $fileIjazah,
                    'skhun' => $fileSkhun
                ]
            ];
        } else {
            $messages = ['status' => 'FAILED', 'pesan: "File ijazah gagal'];
            return response()->json($messages, 417);
        }

        return response()->json($messages, 201);
    }

    private function getValidationAttribute()
    {
        return [
            'nama_lengkap' => 'required',
            'nisn' => 'required',
            'nis' => 'required',
            'jurusan' => 'required',
            'id_tahun' => 'required',
            'tanggal_lahir' => 'required',
            'foto_siswa' => 'mimes:image/jpg,image/png,jpg,png|max:2048'
        ];
    }

    private function failedMessages($err, Request $request)
    {
        $messages = [
            'status' => "FAILED",
            'pesan' => $err,
            'data' => [
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'jurusan' => $request->jurusan,
                'id_tahun_lulus' => $request->tahun_lulus
            ]
        ];
        return $messages;
    }

    public function search(Request $request)
    {
        if (isset($request->nisn)) {
            $querySearch = $request->nisn;
            $searchType = 'nisn';
        } else if (isset($request->nis)) {
            $querySearch = $request->nis;
            $searchType = 'nis';
        } else if (isset($request->nama)) {
            $querySearch = $request->nama;
            $searchType = 'nama';
        }

        $student = Student::where($searchType, 'like',  '%' . $querySearch . '%')->get();

        if ($student->count() > 0) {
            $messages = [
                'status' => 'SUCCESS',
                'pesan' => 'data ditemukan',
                'data' => $student
            ];
            $code = 200;
        } else {
            $messages = [
                'status' => 'FAILED',
                'pesan' => 'data tidak ditemukan'
            ];
            $code = 404;
        }

        return response()->json($messages, $code);
    }
}
