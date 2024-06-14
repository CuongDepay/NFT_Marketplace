<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Services\Contacts\ContactService;

class GetContactCategoryController extends Controller
{
    private ContactService $contactService;

    /**
     * Class constructor.
     */

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
    * @OA\Get(
    * path="/api/contacts/categories",
    * summary="Get contacts categories",
    * tags={"Contacts"},
    * description="Description : get contact categories to contact send email contact.<br>Author: Tan",
    * operationId="getContactCategory",
    * @OA\Response(
    *     response=200,
    *     description="Login successful",
    *     @OA\JsonContent(
    *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
    *         @OA\Property(property="data", type="object", example="{}", description="User information"),
    *     ),
    * ),
    * @OA\Response(
    *     response=500,
    *     description="Internal Server Error",
    *     @OA\JsonContent(
    *        @OA\Property(property="message", type="string", example="Internal Server Error", description="Error message")
    *       )
    *  ),
    *),
    */

    public function __invoke()
    {
        try {
            $contactCategories = $this->contactService->getAllContactCategories();

            return $this->success("Get contact categories successful", $contactCategories);
        } catch (\Exception $e) {
            return $this->failure($e->getMessage());
        }
    }
}
