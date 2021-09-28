<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use App\Models\CopyMiniImage;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Subscribers",   
 * )
 */
class Img extends Model implements HasMedia
{
    /**
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     * @OA\Property(
     *      property="category_img",
     *      type="integer"
     *  ),
     * @OA\Property(
     *      property="title",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="disk",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="directory",
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

    use InteractsWithMedia;

    protected $table = 'imgs';

    protected $fillable = [
        'category_img',
        'title',
        'disk',
        'directory',
        'path_in_disk' 
    ]; 

    /**
     * метод возвращает категорию выбранного изображения
    */
    public function category()
    {
        return $this->belongsTo(CategoryImage::class, 'category_img' , 'id');
    }

    /**
     * метод возвращает все копии от выбранного оригинального изображения
    */
    public function copyMiniImages()
    {
      return $this->hasMany(CopyMiniImage::class , 'id_img' , 'id')->orderBy('created_at','desc');
    }


}













