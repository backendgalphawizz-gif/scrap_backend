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
        'instagram_status',
        'facebook_status',
        'post_slots',
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
