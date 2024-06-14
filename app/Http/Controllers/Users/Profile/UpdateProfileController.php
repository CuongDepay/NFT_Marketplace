<?php

namespace App\Http\Controllers\Users\Profile;

use App\Helpers\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\Users\BasicUserInfoResource;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateProfileController extends Controller
{
    /**
     * @OA\Patch(
     * path="/api/users/profile",
     * summary="Update info profile of user",
     * tags={"User Profile APIs"},
     * description="Update info profile of user.<br>Author: Duc Huy",
     * operationId="updateProfile",
     * @OA\RequestBody(
     *          required=true,
     *          description="Collection information",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string",example="example" ,description="The user's email address."),
     *                  @OA\Property(property="gender", type="int",example="1", description="0 => Male, 1 => Female, 2 => other"),
     *                  @OA\Property(property="custom_url", type="string",example="example.com", description="The user's custom url."),
     *                  @OA\Property(property="phone_number", type="string",example="0123456789", description="The user's phone number."),
     *                  @OA\Property(property="address", type="string",example="200 Street", description="The user's address."),
     *                  @OA\Property(property="introduce", type="string",example="Hello...", description="The user's introduce."),
     *                  @OA\Property(property="country", type="string",example="aa", description="The user's country."),
     *                  @OA\Property(property="state", type="string",example="0123456789", description="The user's state."),
     *                  @OA\Property(property="zip_code", type="string",example="0123456789", description="The user's zip code."),
     *              )
     *          )
     *      ),
     * @OA\Response(
     *     response=200,
     *     description="Update profile successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="code", type="string", example="401", description="Error message"),
     *       @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="User not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="code", type="string", example="404", description="Error message"),
     *       @OA\Property(property="message", type="string", example="User not found!", description="Error message"),
     *     )
     * ),
     * @OA\Response(
     *      response=422,
     *      description="Error: Unprocessable Content",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
     *          @OA\Property(property="errors", type="object", example="{ 'email': ['The email field is required.'],'password': ['The password field is required.'] }", description="Validation errors"),
     *      )
     *   )
     * ),
     */
    public function __invoke(UpdateProfileRequest $request)
    {
        $user = User::find($request->user()->id);

        if (!$user) {
            throw new NotFoundHttpException(MessageConstant::USER_NOT_FOUND);
        }

        $user->name = $request->input('name');
        $user->gender = $request->input('gender');
        $user->custom_url = $request->input('custom_url');
        $user->phone_number = $request->input('phone_number');
        $user->address = $request->input('address');
        $user->introduce = $request->input('introduce');
        $user->country = $request->input('country');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->zip_code = $request->input('zip_code');


        $user->save();

        return $this->success("Update profile successfully", new BasicUserInfoResource($user));
    }
}
