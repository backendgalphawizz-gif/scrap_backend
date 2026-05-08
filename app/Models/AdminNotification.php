<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'related_id',
        'related_type',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query, int $limit = 20)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    // ── Factory helper ────────────────────────────────────────────────────────

    /**
     * Create an admin notification in one call.
     *
     * @param string      $type        Dot-notated event key, e.g. 'brand.registered'
     * @param string      $title       Short heading shown in the bell dropdown
     * @param string      $message     Full description text
     * @param string|null $link        Admin URL to act on this notification
     * @param int|null    $relatedId   PK of the source model record
     * @param string|null $relatedType Short class name of source model, e.g. 'Seller'
     */
    public static function fire(
        string  $type,
        string  $title,
        string  $message,
        ?string $link        = null,
        ?int    $relatedId   = null,
        ?string $relatedType = null
    ): self {
        return static::create(compact('type', 'title', 'message', 'link', 'relatedId', 'relatedType'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Map notification type to a Material Design icon class (mdi-*).
     */
    public function getIconAttribute(): string
    {
        return match (true) {
            str_starts_with($this->type, 'brand.registered')          => 'mdi-store-plus',
            str_starts_with($this->type, 'brand.campaign_submitted')   => 'mdi-bullhorn',
            str_starts_with($this->type, 'brand.campaign_stopped')     => 'mdi-stop-circle',
            str_starts_with($this->type, 'brand.gst_submitted')        => 'mdi-file-certificate',
            str_starts_with($this->type, 'brand.pending_review')       => 'mdi-account-clock',
            str_starts_with($this->type, 'user.pan_submitted')         => 'mdi-card-account-details',
            str_starts_with($this->type, 'user.aadhar_submitted')      => 'mdi-card-account-details-outline',
            str_starts_with($this->type, 'user.upi_requested')         => 'mdi-bank-transfer',
            default                                                     => 'mdi-bell',
        };
    }

    /**
     * Map notification type to a Bootstrap/theme colour class.
     */
    public function getColorAttribute(): string
    {
        return match (true) {
            str_starts_with($this->type, 'brand.campaign_stopped') => 'danger',
            str_starts_with($this->type, 'brand.')                => 'primary',
            str_starts_with($this->type, 'user.')                 => 'warning',
            default                                               => 'secondary',
        };
    }
}
