<?php

namespace App\Http\Controllers\Users\Profile;

use App\Helpers\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangePasswordController extends Controller
{
    /**
     * @OA\Put(
     * path="/api/users/change-password",
     * summary="Change password of account user",
     * tags={"User Profile APIs"},
     * description="Change password of account user.<br>Author: Duc Huy",
     * operationId="changePassword",
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     @OA\Property(property="old_password", type="string",example="123456" ,description="The user's old password."),
     *     @OA\Property(property="new_password", type="string",example="password", description="The user's password."),
     *     @OA\Property(property="new_password_confirmation", type="string",example="password", description="The user's password."),
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Change password successful",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Change password successful", description="Success message"),
     *     ),
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *     )
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Error: Bad request",
     * @OA\JsonContent(
     * @OA\Property(property="code",    type="object", example="400", description="Validation errors"),
     * @OA\Property(property="message", type="string", example="Old password is not correct!", description="Error message"),
     *      )
     *   )
     * ),
     * @OA\Response(
     *      response=422,
     *      description="Error: Unprocessable Content",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
     * @OA\Property(property="errors",  type="object", example="{ 'email': ['The email field is required.'],'password': ['The password field is required.'] }", description="Validation errors"),
     *      )
     *   )
     * ),
     */

    public function __invoke(ChangePasswordRequest $request)
    {
        $user = $this->findUserById($request->user()->id);

        if (!$this->isCorrectOldPassword($request->input('old_password'), $user->password)) {
            throw new BadRequestHttpException(MessageConstant::OLD_PASSWORD_IS_NOT_CORRECT);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();


        return $this->success("Change password successfully");
    }

    private function isCorrectOldPassword($oldPassword, $userPassword)
    {
        return Hash::check($oldPassword, $userPassword);
    }

    private function findUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException(MessageConstant::USER_NOT_FOUND);
        }

        return $user;
    }
}
