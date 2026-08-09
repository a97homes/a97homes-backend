<?php

namespace App\Actions\Country;

use App\Models\Country;

class StoreCountryAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Country
    {
        $flag = $data['flag'] ?? null;
        unset($data['flag']);

        $country = Country::create($data);

        if ($flag) {
            $country->addMedia($flag)->toMediaCollection(Country::MEDIA_COLLECTION_FLAG);
        }

        return $country;
    }
}
