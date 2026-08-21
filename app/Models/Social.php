<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Social extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_ICON = 'social_icon';

    protected $fillable = ['type', 'link'];

    public function getIconUrlAttribute(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl(self::MEDIA_COLLECTION_ICON);

        return $mediaUrl !== '' ? $mediaUrl : null;
    }
}
