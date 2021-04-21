<?php

namespace App\Http\Resources;

use App\Models\AcademicYear;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_siswa' => $this->id,
            'nama_lengkap' => $this->full_name,
            'nisn' => $this->nisn,
            'nis' => $this->nis,
            'tanggal_lahir' => $this->birth_date,
            'tempat_lahir' => $this->birth_place,
            'jenis_kelamin' => $this->gender,
            'slug' => $this->slug,
            'jurusan' => $this->major,
            'id_tahun_akademik' => $this->academic_year_id,
            'foto_siswa' => $this->image,
            'url_foto' => asset('/images/student_images/' . $this->image),
            'ijazah' => $this->graduated_document()->ijazah,
            'skhun' => $this->graduated_document()->skhun,
            'tahun_lulus' => $this->academic_year->year_start . '/' . $this->academic_year->year_end
        ];
    }
}
