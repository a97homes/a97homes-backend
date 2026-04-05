<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\Contact\ContactUsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Contact\ContactRequest;
use App\Http\Resources\API\V1\Contact\ContactResource;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request, ContactUsAction $action): JsonResponse
    {
        $attribute = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.message_created_successfully'),
            data: ContactResource::make($attribute)
        );
    }
}
