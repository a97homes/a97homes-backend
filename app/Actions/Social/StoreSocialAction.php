<?php

namespace App\Actions\Social;

use App\Models\Social;

class StoreSocialAction
{
    public function execute(array $data)
    {
        // TODO:: please change icon, such as https://www.w3schools.com/icons/icons_reference.asp
        $data = collect($data);
        $social = Social::create($data->except(['icon'])->toArray());
        if ($data->has('icon')) {
            $social->addMedia($data->get('icon'))->toMediaCollection('icons');
        }

        return $social;
    }
}
