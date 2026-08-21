<?php

namespace App\Traits;

use App\Enums\ContactMethodTypeEnum;
use App\Models\ContactMethod;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContactMethods
{
    public function contactMethods(): MorphMany
    {
        return $this->morphMany(ContactMethod::class, 'contactable')
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function phones(): MorphMany
    {
        return $this->morphMany(ContactMethod::class, 'contactable')
            ->where('type', ContactMethodTypeEnum::Phone->value)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function whatsappNumbers(): MorphMany
    {
        return $this->morphMany(ContactMethod::class, 'contactable')
            ->where('type', ContactMethodTypeEnum::Whatsapp->value)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
