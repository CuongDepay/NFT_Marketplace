<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\SendContactRequest;
use App\Http\Services\Contacts\ContactService;

class SendContactController extends Controller
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
    * @OA\Post(
    * path="/api/contacts/send",
    * summary="Send contacts",
    * tags={"Contacts"},
    * description="Description : send email to contact and admin.<br>Author: Tan",
    * operationId="sendContact",
    * @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *     @OA\Property(property="name", type="string",example="Name" ,description="name of the contact user."),
    *     @OA\Property(property="email", type="string",example="example@gmail.com" ,description="Email address of the contact user."),
    *     @OA\Property(property="phone_number", type="string",example="Phone number", description="Phone number."),
    *     @OA\Property(property="contact_category_id", type="string",example="Id of Contact Category", description="Choose contact category."),
    *     @OA\Property(property="title", type="string",example="Title", description="Title of contact user."),
    *     @OA\Property(property="message", type="string",example="Message", description="Message of contact user."),
    *     )
    * ),
    * @OA\Response(
    *     response=200,
    *     description="Login successful",
    *     @OA\JsonContent(
    *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
    *         @OA\Property(property="data", type="object", example="{}", description="User information"),
    *     ),
    * ),
    * @OA\Response(
    *     response=400,
    *     description="Bad request",
    *     @OA\JsonContent(
    *          @OA\Property(property="success", type="boolean", example="false", description="Indicates if registration failed"),
    *          @OA\Property(property="message", type="string", example="Bad request", description="Error message"),
    *      ),
    * ),
    * @OA\Response(
    *      response=422,
    *      description="Validation error",
    *      @OA\JsonContent(
    *         @OA\Property(property="success", type="boolean", example="false", description="Indicates if registration failed"),
    *         @OA\Property(property="message", type="string", example="The given data was invalid.", description="Error message"),
    *         @OA\Property(property="errors", type="object", example="{}", description="Validation errors"),
    *       ),
    *    ),
    * @OA\Response(
    *     response=500,
    *     description="Internal Server Error",
    *     @OA\JsonContent(
    *        @OA\Property(property="message", type="string", example="Internal Server Error", description="Error message")
    *       )
    *  ),
    *),
    */

    public function __invoke(SendContactRequest $request)
    {
        try {
            $dataContact = $request->validated();
            $contactCategory =  $this->contactService->findContactCategory($dataContact["contact_category_id"]);
            if ($contactCategory) {
                $dataContact["contact_category_name"] = $contactCategory->name;
                unset($dataContact["contact_category_id"]);

                $this->contactService->sendEmailContact($dataContact);

                return $this->success("Send contact successful");
            }
            return $this->failure("Bad request", 400);
        } catch (\Exception $e) {
            return $this->failure($e->getMessage());
        }
    }
}
