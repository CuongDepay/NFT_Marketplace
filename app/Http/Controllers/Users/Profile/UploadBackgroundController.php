<?php

namespace App\Http\Controllers\Users\Profile;

use App\Helpers\MessageConstant;
use App\Helpers\MinioHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\BackgroundRequest;
use App\Http\Resources\Users\BasicUserInfoResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UploadBackgroundController extends Controller
{

    /**
     * Update background
     *
     * @OA\Post(
     *      path="/api/users/background",
     *      summary="Update background",
     *      tags={"User Profile APIs"},
     *      description="Description: Update background. <br> Author: Duc Huy",
     *      operationId="updateBackground",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Collection information",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"file"},
     *                  @OA\Property(property="file", type="file", format="url", example="https://example.com/logo.jpg", description="URL of the background image (must be JPG, PNG, or JPEG)"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Add view NFT successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Add view NFT successfull"),
     *              @OA\Property(property="data", type="object", example="{}", description="Information about the created NFT")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="NFT not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if NFT creation failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message")
     *          )
     *      ),
     * )
     */

    public function __invoke(BackgroundRequest $request)
    {
        $user = User::find($request->user()->id);
        if (!$user) {
            throw new NotFoundHttpException(MessageConstant::USER_NOT_FOUND);
        }

        $user->background = MinioHelper::uploadFile($request->file);

        $user->save();
        return $this->success("Update background successfully", new BasicUserInfoResource($user));
    }
}
