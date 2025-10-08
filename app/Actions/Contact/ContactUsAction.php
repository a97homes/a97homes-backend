<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class ContactUsAction
{
    public function execute(array $data): Contact
    {
        return Contact::create($data);
    }
}
