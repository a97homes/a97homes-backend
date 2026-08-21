<?php

namespace App\Actions\Country;

use App\Models\Country;

class UpdateCountryAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Country $country, array $data): Country
    {
        $flag = $data['flag'] ?? null;
        unset($data['flag']);

        $country->update($data);

        if ($flag) {
            $country->clearMediaCollection(Country::MEDIA_COLLECTION_FLAG);
            $country->addMedia($flag)->toMediaCollection(Country::MEDIA_COLLECTION_FLAG);
        }

        return $country;
    }
}
