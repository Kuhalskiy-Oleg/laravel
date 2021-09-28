<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiImgController;
use App\Http\Controllers\Api\ApiCopyMiniImageController;
use App\Http\Controllers\Api\ApiSubscriberController;
use App\Http\Controllers\Api\ApiCategoryImageController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResources([
    '/images' => ApiImgController::class,    
    '/categories' => ApiCategoryImageController::class,
    '/subscribers' => ApiSubscriberController::class,
    '/copy-images' => ApiCopyMiniImageController::class,
]);


// АДРЕСА ПО КОТОРЫМ МОЖНО ДОСТУЧАТЬСЯ ДО Resources КОНТРОЛЛЕРА:

// Method        URI                      Action       Route_Name

// GET           /articles                index        V1.articles.index    для показа всего
// POST          /articles                store        V1.articles.store    для сохранения
// GET           /articles/{id}           show         V1.articles.show     для показа выбранных
// PUT/PATCH     /articles/{id}           update       V1.articles.update   для обновления
// DELETE        /articles/{id}           destroy      V1.articles.destroy  для удаления

// В apiResources НЕ БУДЕТ МЕТОДА create И edit

// GET           /articles/create         create       V1.articles.create   для создания
// GET           /articles/{id}/edit      edit         V1.articles.edit     для редактирования




