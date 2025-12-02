<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'bio',
        'avatar_url',
    ];

    /**
     * The events that this speaker is associated with.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_speakers');
    }

    public function getAvatarUrlAttribute($value)
    {
        return $value ? asset( 'storage/' . $value) : null;
    }
}
