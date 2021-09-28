<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     type="object",
 *     title="CopyMiniImage",   
 * )
 */
class CopyMiniImage extends Model
{
    /**
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     * @OA\Property(
     *      property="id_img",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="title",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="disk",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="path_in_disk",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="created_at",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="updated_at",
     *      type="string"
     *  ),
     */

    use HasFactory;

    protected $table = 'copy_mini_images';

    protected $fillable = [
        'id_img',
        'title',
        'disk',
        'path_in_disk',
    ]; 

    /**
     * метод возвращает оригинальную картинку по которой была сделана копия
    */
    public function originalImage()
    {
        return $this->belongsTo(Img::class, 'id_img' , 'id');
    }
}
