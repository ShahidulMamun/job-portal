<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'website', 'location', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
    ];

    public function jobs()
    {
        return $this->hasMany(JobPost::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Public URL of the logo, or null. */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /** "TechCorp Solutions" -> "TS" (used when there is no logo). */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/u', trim($this->name), -1, PREG_SPLIT_NO_EMPTY);
        return mb_strtoupper(collect($words)->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    }

    public function deleteLogoFile(): void
    {
        if ($this->logo) {
            Storage::disk('public')->delete($this->logo);
        }
    }
}