<?php

namespace App\Actions\Consultant;

use App\Actions\Media\AddMediaAction;
use App\Models\Consultant;
use Illuminate\Http\UploadedFile;

class StoreConsultantAction
{
    public function __construct(private readonly AddMediaAction $addMedia) {}

    public function execute(array $data, ?UploadedFile $image = null, ?UploadedFile $coverImage = null): Consultant
    {
        $phones = $data['phones'] ?? [];
        unset($data['phones'], $data['image'], $data['cover_image']);

        $consultant = Consultant::create($data);

        foreach ($phones as $phone) {
            $consultant->phones()->create(['phone' => $phone]);
        }

        if ($image !== null) {
            $this->addMedia->execute($consultant, ['file' => $image], Consultant::MEDIA_COLLECTION_IMAGE);
        }

        if ($coverImage !== null) {
            $this->addMedia->execute($consultant, ['file' => $coverImage], Consultant::MEDIA_COLLECTION_COVER);
        }

        return $consultant->load(['phones', 'media']);
    }
}
