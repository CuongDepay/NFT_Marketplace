<?php

namespace App\Http\Controllers\Users\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\MinioHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\MessageConstant;
use App\Http\Requests\File\UploadFileRequest;
use App\Http\Resources\Users\BasicUserInfoResource;

class UpdateAvatarController extends Controller
{
    private MinioHelper $minioHelper;

    public function __construct(MinioHelper $minioHelper)
    {
        $this->minioHelper = $minioHelper;
    }

      /**
     * Update avatar
     *
     * @OA\Post(
     *      path="/api/users/avatar",
     *      summary="Update avatar",
     *      tags={"User Profile APIs"},
     *      description="Description: Update avatar. <br> Author: Duc Huy",
     *      operationId="updateAvatar",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Collection information",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"file"},
     *                  @OA\Property(property="file", type="file", format="url", example="https://example.com/logo.jpg", description="URL of the collection's logo image (must be JPG, PNG, or JPEG)"),
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

    public function __invoke(UploadFileRequest $request)
    {
        $user = User::find($request->user()->id);

        if (!$user) {
            throw new NotFoundHttpException(MessageConstant::USER_NOT_FOUND);
        }

        $user->avatar = $this->minioHelper->uploadFile($request->file);
        $user->save();

        return $this->success("Update profile successfully", new BasicUserInfoResource($user));
    }
}