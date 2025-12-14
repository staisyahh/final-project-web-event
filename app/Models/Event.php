<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'jadwal',
        'location_name',
        'location_address',
        'banner_url',
        'status',
        'category_id',
        'organizer_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jadwal' => 'datetime',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = [
    //     'category',
    //     'organizer',
    // ];

    /**
     * Accessor for banner_url to get full path.
     */
    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
        );
    }

    /**
     * Get the review for the event by the currently authenticated user.
     */
    public function userReview(): HasOne
    {
        return $this->hasOne(Review::class)->where('user_id', Auth::id());
    }

    /**
     * Get the category that owns the event.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user (organizer) that owns the event.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * The speakers that belong to the event.
     */
    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class, 'event_speakers');
    }

    /**
     * Get the tickets for the event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the e-tickets for the event.
     */
    public function eTickets(): HasMany
    {
        return $this->hasMany(ETicket::class);
    }

    /**
     * Get the gallery images for the event.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(EventGallery::class);
    }

    /**
     * Get the reviews for the event.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The users that have bookmarked the event.
     */
    public function bookmarkedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }
}
