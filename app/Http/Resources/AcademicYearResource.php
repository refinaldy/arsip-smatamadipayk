<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademicYearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $messages = [];

        return [
            'id' => $this->id,
            'tahun_awal' => $this->year_start,
            'tahun_akhir' => $this->year_end,
            'jumlah_siswa' => count($this->students),
            'siswa' => $this->students

        ];
    }
}
