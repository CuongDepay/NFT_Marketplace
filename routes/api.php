<?php

use App\Http\Controllers\Cart\AddNFTToCartController;
use App\Http\Controllers\Cart\GetMyCartController;
use App\Http\Controllers\Cart\RemoveNFTInCartController;
use App\Http\Controllers\Cart\UpdateNFTInCartController;
use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\Collection\CollectionController;
use App\Http\Controllers\Collection\GetCollectionDetailController;
use App\Http\Controllers\Collection\GetListCollectionCategoriesController;
use App\Http\Controllers\Collection\GetListCollectionController;
use App\Http\Controllers\Collection\GetMyCollectionController;
use App\Http\Controllers\Collection\GetTopCollectionController;
use App\Http\Controllers\Contacts\GetContactCategoryController;
use App\Http\Controllers\Contacts\SendContactController;
use App\Http\Controllers\Favourite\AddNFTtoFavouriteController;
use App\Http\Controllers\Favourite\GetListFavouriteByIdUserController;
use App\Http\Controllers\Favourite\RemoveFavouriteController;
use App\Http\Controllers\File\UploadFileController;
use App\Http\Controllers\NFTs\AddViewNFTController;
use App\Http\Controllers\NFTs\CreateNFTController;
use App\Http\Controllers\NFTs\GetDetailNFTController;
use App\Http\Controllers\NFTs\GetListNFTByCollectionIdController;
use App\Http\Controllers\NFTs\GetListNFTController;
use App\Http\Controllers\NFTs\GetTopNFTController;
use App\Http\Controllers\Notifications\NotificationCategories\GetNotificationCategories;
use App\Http\Controllers\Notifications\Notifications\GetCountUnreadNotificationController;
use App\Http\Controllers\Notifications\Notifications\GetListNotification;
use App\Http\Controllers\Notifications\Notifications\MarkAllReadNotificationController;
use App\Http\Controllers\Notifications\Notifications\MarkReadNotificationIdController;
use App\Http\Controllers\Notifications\NotificationSetting\GetListNotificationSettingController;
use App\Http\Controllers\Notifications\NotificationSetting\SettingNotificationController;
use App\Http\Controllers\UserRelationship\FollowController;
use App\Http\Controllers\UserRelationship\GetUserAndFollowersListController;
use App\Http\Controllers\UserRelationship\UnFollowController;
use App\Http\Controllers\Users\Auth\LoginController;
use App\Http\Controllers\Users\Auth\LogoutController;
use App\Http\Controllers\Users\Detail\GetUserDetailController;
use App\Http\Controllers\Users\GetTopSellerController;
use App\Http\Controllers\Users\Profile\ChangePasswordController;
use App\Http\Controllers\Users\Auth\RegisterController;
use App\Http\Controllers\Users\Auth\ResendVerifyEmailController;
use App\Http\Controllers\Users\Auth\ResetPasswordController;
use App\Http\Controllers\Users\Auth\ForgotPasswordController;
use App\Http\Controllers\Users\Auth\VerifyEmailController;
use App\Http\Controllers\Users\Profile\GetProfileController;
use App\Http\Controllers\Users\Profile\UpdateAvatarController;
use App\Http\Controllers\Users\Profile\UpdateProfileController;
use App\Http\Controllers\Users\Profile\UploadBackgroundController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/auth")->group(function () {
    Route::post("/login", LoginController::class);
    Route::post("/logout", LogoutController::class)->middleware("auth");
    Route::post("/register", RegisterController::class);
    Route::get("/resend-verify-email", ResendVerifyEmailController::class);
    Route::get("/verify-email/{token}", VerifyEmailController::class);
    Route::get("/forgot-password", ForgotPasswordController::class);
    Route::post("/reset-password/{token}", ResetPasswordController::class);
});

Route::prefix("/users")->group(function () {
    Route::get("/top-seller", GetTopSellerController::class);
    Route::get("/my-profile", GetProfileController::class)->middleware("auth");
    Route::post("/profile", UpdateProfileController::class)->middleware("auth");
    Route::put("/change-password", ChangePasswordController::class)->middleware("auth");
    Route::get("/{userId}", GetUserDetailController::class);
    Route::post("/background", UploadBackgroundController::class)->middleware("auth");
    Route::post("/avatar", UpdateAvatarController::class)->middleware("auth");
});

Route::prefix("/nfts")->group(function () {
    Route::get("/get-top", GetTopNFTController::class);
    Route::get("/{nftId}", GetDetailNFTController::class);

    Route::post("/{nftId}/views", AddViewNFTController::class);
    Route::post("", CreateNFTController::class)->middleware("auth");
    Route::get("", GetListNFTController::class);
});

Route::prefix("/collections")->group(function () {
    Route::get("/top", GetTopCollectionController::class);
    Route::post("", [CollectionController::class, 'storeCollection'])->middleware("auth");
    Route::get("/collectionsList", GetListCollectionController::class);
    Route::get("/{collectionId}", GetCollectionDetailController::class);
    Route::get("", GetMyCollectionController::class)->middleware("auth");
    Route::post("/{collectionId}/nfts", CreateNFTController::class)->middleware("auth");
    Route::get("/{collectionId}/nfts", GetListNFTByCollectionIdController::class);

});
Route::get("/collection-categories", GetListCollectionCategoriesController::class);
Route::prefix("/favourites")->group(function () {
    Route::post("", AddNFTtoFavouriteController::class)->middleware("auth");
    Route::get("/{userId}", GetListFavouriteByIdUserController::class);
    Route::delete("/{nftId}", RemoveFavouriteController::class)->middleware("auth");
});

Route::prefix("/contacts")->group(function () {
    Route::get("/categories", GetContactCategoryController::class);
    Route::post("/send", SendContactController::class);
});

Route::prefix("/notification-setting")->middleware("auth")->group(function () {
    Route::get("/", GetListNotificationSettingController::class);
    Route::put("/", SettingNotificationController::class);
});

Route::prefix("/notification-categories")->middleware("auth")->group(function () {
    Route::get("/", GetNotificationCategories::class);
});

Route::prefix("/notifications")->middleware("auth")->group(function () {
    Route::get("/", GetListNotification::class);
    Route::put("/mark-all-read", MarkAllReadNotificationController::class);
    Route::put("/{id}", MarkReadNotificationIdController::class);
    Route::get("/count-unread", GetCountUnreadNotificationController::class);
});

Route::prefix("/cart")->middleware("auth")->group(function () {
    Route::post("/", AddNFTToCartController::class);
    Route::put("/", UpdateNFTInCartController::class);
    Route::delete("/{nft_id}", RemoveNFTInCartController::class);
    Route::get("", GetMyCartController::class);
});

Route::prefix("/user-relationship")->group(function () {
    Route::get("/users", GetUserAndFollowersListController::class);
    Route::post("/{user_id}", FollowController::class)->middleware("auth");
    Route::delete("/{user_id}", UnFollowController::class)->middleware("auth");
});

Route::post("/checkout", CheckoutController::class)->middleware("auth");

Route::post("/files/upload", UploadFileController::class);
