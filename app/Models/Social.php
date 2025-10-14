<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Social extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_SOCIAL = 'social';

    protected $fillable = ['type', 'link'];
}
