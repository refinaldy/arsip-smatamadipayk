<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'id' => $this->id,
            'nama_acara' => $this->event_name,
            'penyelenggara' => $this->organizer,
            'tanggal_acara' => $this->event_date,
            'slug' => $this->slug,
            'dokumentasi_acara' => $this->achievement_documentations,
            'piagam' => $this->achievement_charter,
            'kategori_juara' => $this->achievement_rank->rank,
            'kategori_lomba' => $this->achievement_category->category,
            'siswa' => $this->students
        ];
    }
}
