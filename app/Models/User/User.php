<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\User\TokenAbilityEnum;
use App\Filters\CreatedAtFilter;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use CreatedAtFilter;
    use HasApiTokens;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_AVATAR = 'user_avatar';

    protected $guard_name = 'web';

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    use HasRoles;
    use Notifiable;
    use UserAccessor;
    use UserAction;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'country_code',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            'abilities' => TokenAbilityEnum::class,

        ];

    }

    public function createToken(string $name, array $abilities = ['*'], $expiresAt = null)
    {
        $expiration = (int) config('sanctum.expiration');
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(240)),
            'abilities' => $abilities,
            'expires_at' => Carbon::now()->addMinutes((int) $expiresAt ?? $expiration),
        ]);

        return new NewAccessToken($token, $token->id.'|'.$plainTextToken);
    }
}
