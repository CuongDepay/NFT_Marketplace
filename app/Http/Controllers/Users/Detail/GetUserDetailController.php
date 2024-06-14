<?php

namespace App\Http\Controllers\Users\Detail;

use App\Http\Controllers\Controller;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Resources\Users\UserDetailByIdResource;
use App\Http\Services\Users\UserService;
use Illuminate\Http\Request;

class GetUserDetailController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users/{userId}",
     *     summary="Get user detail and list nfts",
     *     tags={"User Profile APIs"},
     *     description="Get user detail and list nfts by user id.",
     *     operationId="getUserDetailNfts",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="Id of user",
     *         example="9c002343-7853-454b-b489-a5d10635ea94",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         example=10,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get user detail successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Get detail user successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(
     *                          property="followers",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="string"),
     *                          )
     *                     ),
     *                     @OA\Property(
     *                          property="followings",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="string"),
     *                          )
     *                     ),
     *                 ),
     *             ),
     *             @OA\Property(
     *                 property="nfts",
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="nft_id", type="integer"),
     *                         @OA\Property(property="nft_name", type="string"),
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer"),
     *                     @OA\Property(property="from", type="integer"),
     *                     @OA\Property(property="last_page", type="integer"),
     *                     @OA\Property(property="path", type="string"),
     *                     @OA\Property(property="next_page_url", type="string"),
     *                     @OA\Property(property="last_page_url", type="string"),
     *                     @OA\Property(property="per_page", type="integer"),
     *                     @OA\Property(property="to", type="integer"),
     *                     @OA\Property(property="total", type="integer"),
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found"),
     *         ),
     *     ),
     * )
     */



    public function __invoke($id, Request $request)
    {
        $result = $this->userService->getDetailUserById($id, $request->input("pageSize"));

        $user = $result["user"];
        $nfts = $result["nfts"];

        return $this->success("Get detail user successfully", [
            "user" => new UserDetailByIdResource($user),
            "nfts" => [
                "data" => NFTInfoResource::collection($nfts),
                'meta' => [
                    'current_page' => $nfts->currentPage(),
                    'from' => $nfts->firstItem(),
                    'last_page' => $nfts->lastPage(),
                    'path' => $nfts->path(),
                    "next_page_url" => $nfts->nextPageUrl(),
                    "last_page_url" => $nfts->previousPageUrl(),
                    'per_page' => $nfts->perPage(),
                    'to' => $nfts->lastItem(),
                    'total' => $nfts->total(),
                ]
            ]
        ]);
    }
}
