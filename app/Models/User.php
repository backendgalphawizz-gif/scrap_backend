<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the trait

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::created(function (self $user): void {
            if (empty($user->unique_code)) {
                $user->forceFill([
                    'unique_code' => 'RX-' . $user->id,
                ])->saveQuietly();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'mobile',
        'internal_id',
        'gmr_ci_id',
        'gmr_mi_id',
        'circle_assignment',
        'image',
        'actual_password',
        'status',
        'otp',
        'otp_expires_at',
        "dob",
        "profession",
        "gender",
        'city',
        'state',
        'native_state',
        'native_city',
        'referral_code',
        'friends_code',
        'unique_code',
        'provider',
        'provider_id',
        'instagram_username',
        'facebook_username',
        'threads_username',
        'instagram_status',
        'facebook_status',
        'threads_status',
        'post_slots',
        'my_interest',
        'fcm_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function campaigns()
    {
        return $this->hasMany(CampaignTransaction::class, 'user_id');
    }

    public function campaignSkips()
    {
        return $this->hasMany(UserCampaignSkip::class, 'user_id');
    }

    public function socialVerifications()
    {
        return $this->hasMany(SocialVerificationTransaction::class, 'user_id');
    }

    public function latestSocialVerification(string $platform): ?SocialVerificationTransaction
    {
        return $this->socialVerifications()
            ->where('platform', $platform)
            ->latest()
            ->first();
    }

    /** Raw DB value — avoids the image accessor always returning a default URL. */
    public function rawImagePath(): ?string
    {
        $value = $this->attributes['image'] ?? null;

        return filled($value) ? $value : null;
    }

    public function profileImageUrl(): string
    {
        $value = $this->rawImagePath();
        if (! $value) {
            return asset('public/assets/front-end/img/image-place-holder.png');
        }

        return str_starts_with($value, 'http://') || str_starts_with($value, 'https://')
            ? $value
            : asset('storage/profile/' . ltrim($value, '/'));
    }

    public function resolvedSocialUsername(string $platform): ?string
    {
        $field = $platform . '_username';
        $value = trim((string) ($this->{$field} ?? ''));
        if ($value !== '') {
            return $value;
        }

        $fromVerification = trim((string) ($this->latestSocialVerification($platform)?->username ?? ''));

        return $fromVerification !== '' ? $fromVerification : null;
    }

    /** User started verification or has a non–not_submitted status. */
    public function hasSubmittedSocial(string $platform): bool
    {
        $statusField = $platform . '_status';
        $status = (string) ($this->{$statusField} ?? 'not_submitted');

        if ($status !== 'not_submitted') {
            return true;
        }

        return $this->latestSocialVerification($platform) !== null;
    }

    /** Username shown in admin only when the user actually submitted social details. */
    public function adminDisplaySocialUsername(string $platform): ?string
    {
        if (! $this->hasSubmittedSocial($platform)) {
            return null;
        }

        return $this->resolvedSocialUsername($platform);
    }

    public function adminDisplaySocialStatus(string $platform): string
    {
        if (! $this->hasSubmittedSocial($platform)) {
            return 'not_submitted';
        }

        return (string) ($this->{$platform . '_status'} ?? 'not_submitted');
    }

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
        ];
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function coinWallet()
    {
        return $this->hasOne(CoinWallet::class);
    }

    public function referrers()
    {
        return $this->hasMany(User::class, 'friends_code', 'referral_code')->select('id', 'name', 'mobile', 'email', 'image', 'referral_code');
    }

    public function hasPermission($permission)
    {
        // return $this->role
        //     && $this->role->permissions->contains('name', $permission);

        //     if ($this->role && $this->role->name === 'admin') {
        //     return true;
        // }

        return $this->role
            && $this->role->permissions->contains('name', $permission);
    }

    public function getBankDetailAttribute($value)
    {
        return json_decode($value);
    }

    public function getImageAttribute($value)
    {
        return (strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . ($value ?: 'def.png'));
    }

    public function getPanImageAttribute($value)
    {
        return $value ? ((strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . $value)) : null;
    }

    public function getAadharImageAttribute($images)
    {
        $newArr = [];
        $images = explode(',', $images);
        foreach ($images as $key => $value) {
            $newArr[] = $value ? ((strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . $value)) : null;
        }
        return $newArr;
    }

}
