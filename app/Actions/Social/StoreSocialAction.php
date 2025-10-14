<?php

namespace App\Actions\Social;

use App\Models\Social;

class StoreSocialAction
{
    public function execute(array $data)
    {

        $data = collect($data);
        $social = Social::create($data->except(['icon'])->toArray());
        if ($data->has('icon')) {
            $social->addMedia($data->get('icon'))->toMediaCollection('icons');
        }

        return $social;
    }
}
