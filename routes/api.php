<?php

use Illuminate\Support\Facades\Route;

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

Route::get('auth/email/verify/{id}', [App\Http\Controllers\Api\VerificationController::class, 'verify'])->name('verification.verify');
Route::get('auth/email/resend', [App\Http\Controllers\Api\VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware('auth.apikey')->group(
    function () {


        Route::post('auth/password/forgot', [App\Http\Controllers\Api\UserController::class, 'forgot']);
        Route::post('auth/password/reset', [App\Http\Controllers\Api\UserController::class, 'reset'])->name('password.reset');

        Route::post('auth/register', [App\Http\Controllers\Api\UserController::class, 'register']);
        Route::post('auth/login', [App\Http\Controllers\Api\UserController::class, 'login']);

        Route::post('auth/login/facebook', [App\Http\Controllers\Api\SocialApiAuthFacebookController::class, 'facebookConnect']);
        //  Route::post('auth/login/linkedin', [App\Http\Controllers\Api\SocialApiAuthLinkedinController::class, 'linkedinConnect']);
        Route::post('auth/login/google', [App\Http\Controllers\Api\SocialApiAuthGoogleController::class, 'googleConnect']);
        Route::post('auth/login/osm', [App\Http\Controllers\Api\SocialApiAuthOsmController::class, 'osmConnect']);

        //GroupeCarte
        Route::get('groupecartes', [App\Http\Controllers\Api\GroupeCarteController::class, 'index']);
        Route::get('groupecartes/{id}', [App\Http\Controllers\Api\GroupeCarteController::class, 'show']);

        //Carte
        Route::get('cartes', [App\Http\Controllers\Api\CarteController::class, 'index']);
        Route::get('cartes/{id}', [App\Http\Controllers\Api\CarteController::class, 'show']);

        //Thematiques
        Route::get('thematiques', [App\Http\Controllers\Api\ThematiqueController::class, 'index']);
        Route::get('thematiques/{id}', [App\Http\Controllers\Api\ThematiqueController::class, 'show']);

        //SousThematiques
        Route::get('sousthematiques', [App\Http\Controllers\Api\SousThematiqueController::class, 'index']);
        Route::get('sousthematiques/{id}', [App\Http\Controllers\Api\SousThematiqueController::class, 'show']);

        //Couche
        Route::get('couches', [App\Http\Controllers\Api\CoucheController::class, 'index']);
        Route::get('couches/{id}', [App\Http\Controllers\Api\CoucheController::class, 'show']);
        Route::get('search/couches', [App\Http\Controllers\Api\CoucheController::class, 'searchCouche']);

        //Tag
        Route::get('tags', [App\Http\Controllers\Api\TagController::class, 'index']);
        Route::get('tags/{id}', [App\Http\Controllers\Api\TagController::class, 'show']);

        //Instance
        Route::get('instances', [App\Http\Controllers\Api\InstanceController::class, 'index']);
        Route::get('instances/{id}', [App\Http\Controllers\Api\InstanceController::class, 'show']);

        //Commentaire
        Route::get('commentaires', [App\Http\Controllers\Api\CommentaireController::class, 'index']);
        Route::get('commentaires/{id}', [App\Http\Controllers\Api\CommentaireController::class, 'show']);

        //Metadatas
        Route::get('metadatas', [App\Http\Controllers\Api\MetadataController::class, 'index']);
        Route::get('metadatas/{id}', [App\Http\Controllers\Api\MetadataController::class, 'show']);

        //Ouvrage
        Route::get('ouvrages', [App\Http\Controllers\Api\OuvrageController::class, 'index']);
        Route::post('ouvragesbysyndicat', [App\Http\Controllers\Api\OuvrageController::class, 'searchBySyndicat']);

        Route::post('ouvragesbydepartement', [App\Http\Controllers\Api\OuvrageController::class, 'searchByDepartement']);

        Route::post('ouvragesbycommune', [App\Http\Controllers\Api\OuvrageController::class, 'searchByCommune']);

        Route::post('ouvragesglobalsearch', [App\Http\Controllers\Api\OuvrageController::class, 'globalSearch']);
        Route::get('ouvrages/{id}', [App\Http\Controllers\Api\OuvrageController::class, 'show']);

        //Draw
        Route::get('draws', [App\Http\Controllers\Api\DrawController::class, 'index']);
        Route::get('draws/code/{code}', [App\Http\Controllers\Api\DrawController::class, 'getAllDrawByCode']);
        Route::get('draws/{id}', [App\Http\Controllers\Api\DrawController::class, 'show']);
        Route::post('draws', [App\Http\Controllers\Api\DrawController::class, 'store']);
        Route::put('draws/{id}', [App\Http\Controllers\Api\DrawController::class, 'update']);



        Route::middleware('auth:api')->group(function () {
            Route::get('auth/logout', [App\Http\Controllers\Api\UserController::class, 'logout']);
            Route::post('user/update/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
            Route::delete('user/delete/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
            Route::get('user/me', [App\Http\Controllers\Api\UserController::class, 'me']);

            Route::post('groupecartes', [App\Http\Controllers\Api\GroupeCarteController::class, 'store']);
            Route::put('groupecartes/{id}', [App\Http\Controllers\Api\GroupeCarteController::class, 'update']);
            Route::delete('groupecartes/{id}', [App\Http\Controllers\Api\GroupeCarteController::class, 'destroy']);


            Route::post('cartes', [App\Http\Controllers\Api\CarteController::class, 'store']);
            Route::put('cartes/{id}', [App\Http\Controllers\Api\CarteController::class, 'update']);
            Route::delete('cartes/{id}', [App\Http\Controllers\Api\CarteController::class, 'destroy']);

            Route::post('thematiques', [App\Http\Controllers\Api\ThematiqueController::class, 'store']);
            Route::put('thematiques/{id}', [App\Http\Controllers\Api\ThematiqueController::class, 'update']);
            Route::delete('thematiques/{id}', [App\Http\Controllers\Api\ThematiqueController::class, 'destroy']);

            Route::post('sousthematiques', [App\Http\Controllers\Api\SousThematiqueController::class, 'store']);
            Route::put('sousthematiques/{id}', [App\Http\Controllers\Api\SousThematiqueController::class, 'update']);
            Route::delete('sousthematiques/{id}', [App\Http\Controllers\Api\SousThematiqueController::class, 'destroy']);

            Route::post('couches', [App\Http\Controllers\Api\CoucheController::class, 'store']);
            Route::put('couches/{id}', [App\Http\Controllers\Api\CoucheController::class, 'update']);
            Route::delete('couches/{id}', [App\Http\Controllers\Api\CoucheController::class, 'destroy']);

            Route::post('tags', [App\Http\Controllers\Api\TagController::class, 'store']);
            Route::put('tags/{id}', [App\Http\Controllers\Api\TagController::class, 'update']);
            Route::delete('tags/{id}', [App\Http\Controllers\Api\TagController::class, 'destroy']);

            Route::post('instances', [App\Http\Controllers\Api\InstanceController::class, 'store']);
            Route::put('instances/{id}', [App\Http\Controllers\Api\InstanceController::class, 'update']);
            Route::delete('instances/{id}', [App\Http\Controllers\Api\InstanceController::class, 'destroy']);

            Route::post('commentaires', [App\Http\Controllers\Api\CommentaireController::class, 'store']);
            Route::put('commentaires/{id}', [App\Http\Controllers\Api\CommentaireController::class, 'update']);
            Route::delete('commentaires/{id}', [App\Http\Controllers\Api\CommentaireController::class, 'destroy']);

            Route::post('metadatas', [App\Http\Controllers\Api\MetadataController::class, 'store']);
            Route::put('metadatas/{id}', [App\Http\Controllers\Api\MetadataController::class, 'update']);
            Route::delete('metadatas/{id}', [App\Http\Controllers\Api\MetadataController::class, 'destroy']);

            Route::delete('draws/{id}', [App\Http\Controllers\Api\DrawController::class, 'destroy']);


            Route::group(['middleware' => ['role:admin']], function () {
                Route::apiResource('roles', App\Http\Controllers\Api\RoleController::class);
                Route::apiResource('permissions', App\Http\Controllers\Api\PermissionController::class);
            });
        });
    }
);
