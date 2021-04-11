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
            'ijazah' => $this->ijazah_file,
            'skhun' => $this->skhun_file,
            'tahun_lulus' => $this->academic_year->year_start . '/' . $this->academic_year->year_end
        ];
    }
}
