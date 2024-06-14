<?php

namespace App\Http\Services\Contacts;

use App\Http\Resources\Contacts\ContactCategoryResource;
use App\Jobs\SendContactEmail;
use App\Mail\SendContactMail;
use App\Models\ContactCategory;

class ContactService
{
    public function getAllContactCategories()
    {
        return ContactCategoryResource::collection(ContactCategory::all());
        ;
    }

    public function findContactCategory($id)
    {
        return ContactCategory::all()->find($id);
    }

    public function sendEmailContact($dataContact)
    {
        dispatch(new SendContactEmail($dataContact));
    }
}
