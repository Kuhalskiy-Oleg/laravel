<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CopyMiniImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'origin_img' => $this->originalImage,
            'title'=> $this->title,
            'disk'=> $this->disk,
            'path_in_disk'=> $this->path_in_disk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
