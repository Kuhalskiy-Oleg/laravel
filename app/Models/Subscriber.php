<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Subscribers",   
 * )
 */
class Subscriber extends Model
{
    /**
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     * @OA\Property(
     *      property="name",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="email",
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

    protected $table = 'subscribers';

    protected $fillable = [
        'name',
        'email',
        'slug', 
    ]; 

    /**
     * метод возвращает подписки выбранного пользователя
    */
    public function categoryImages()
    {
        return $this->belongsToMany(CategoryImage::class, 'selected_categories', 'subscribers_id', 'category_images_id');
    }
}
