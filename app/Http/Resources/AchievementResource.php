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
        $documentations = explode(',',  $this->achievement_documentations);
        $url = array();
        foreach ($documentations as $doc) {
            array_push($url, secure_asset('/images/achievement_documentation/' . $doc));
        }
        $documentationsURL = implode(',', $url);
        $charterURL = secure_asset('images/achievements_charter/' . $this->achievement_charter);

        return [
            'id' => $this->id,
            'nama_acara' => $this->event_name,
            'penyelenggara' => $this->organizer,
            'tanggal_acara' => $this->event_date,
            'slug' => $this->slug,
            'dokumentasi_acara' => $this->achievement_documentations,
            'link_dokumentasi' => $documentationsURL,
            'link_piagam' => $charterURL,
            'kategori_juara' => $this->achievement_rank->rank,
            'kategori_lomba' => $this->achievement_category->category,
            'siswa' => $this->students
        ];
    }
}
