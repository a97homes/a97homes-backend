<?php

namespace App\Actions\Consultant;

use App\Actions\Media\AddMediaAction;
use App\Models\Consultant;
use Illuminate\Http\UploadedFile;

class UpdateConsultantAction
{
    public function __construct(private readonly AddMediaAction $addMedia) {}

    public function execute(Consultant $consultant, array $data, ?UploadedFile $image = null, ?UploadedFile $coverImage = null): Consultant
    {
        $phones = $data['phones'] ?? null;
        unset($data['phones'], $data['image'], $data['cover_image']);

        $consultant->update($data);

        if ($phones !== null) {
            $consultant->phones()->delete();

            foreach ($phones as $phone) {
                $consultant->phones()->create(['phone' => $phone]);
            }
        }

        if ($image !== null) {
            $consultant->clearMediaCollection(Consultant::MEDIA_COLLECTION_IMAGE);
            $this->addMedia->execute($consultant, ['file' => $image], Consultant::MEDIA_COLLECTION_IMAGE);
        }

        if ($coverImage !== null) {
            $consultant->clearMediaCollection(Consultant::MEDIA_COLLECTION_COVER);
            $this->addMedia->execute($consultant, ['file' => $coverImage], Consultant::MEDIA_COLLECTION_COVER);
        }

        return $consultant->load(['phones', 'media']);
    }
}
