<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     type="object",
 *     title="CategoryImage",   
 * )
 */
class CategoryImage extends Model 
{
    /**
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="title",
     *      type="string"
     *  ),
     * @OA\Property(
     *      property="slug",
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

    protected $table = 'category_images';

    protected $fillable = [
        'title',
        'slug',
    ];


    /**
     * метод возвращает всех подписчиков от выбранной категории
    */
    public function subscribers()
    {  
        return $this->belongsToMany(Subscriber::class, 'selected_categories', 'category_images_id', 'subscribers_id')->withTimestamps();
    } 
}
