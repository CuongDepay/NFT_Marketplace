<?php

namespace App\Http\Controllers\File;

use App\Helpers\MinioHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UploadFileRequest;

class UploadFileController extends Controller
{
    private MinioHelper $minioHelper;

    public function __construct(MinioHelper $minioHelper)
    {
        $this->minioHelper = $minioHelper;
    }

    /**
     * Upload file
     *
     * @OA\Post(
     *      path="/api/files/upload",
     *      summary="Upload file",
     *      tags={"File APIs"},
     *      description="Description: Upload file. <br> Author: Duc Huy",
     *      operationId="uploadFile",
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
        return $this->success("Upload file successful!", $this->minioHelper->uploadFile($request->file));
    }
}