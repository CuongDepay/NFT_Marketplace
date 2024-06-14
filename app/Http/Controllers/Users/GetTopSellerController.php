<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Services\Users\UserService;
use Illuminate\Http\Request;

class GetTopSellerController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get the top sellers.
     *
     * @OA\Get(
     *     path="/api/users/top-seller",
     *     summary="Get top sellers",
     *     tags={"Users"},
     *     description="Get the list of top sellers based on total sales amount",
     *     @OA\Response(
     *         response=200,
     *         description="List of top sellers",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="total_sales", type="number", example="1000.00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request)
    {
        $topSeller = $this->userService->getTopSeller();
        return $this->success("Get Top Seller successfully", $topSeller, 200);
    }
}
