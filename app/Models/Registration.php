<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Casts\Attribute;



class Registration extends Model

{

    use HasFactory;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'user_id',

        'ticket_id',

        'event_id',

        'jumlah_tiket',

        'total_bayar',

        'status',

        'payment_proof_path',

    ];



    /**

     * The attributes that should be cast.

     *

     * @var array

     */

    protected $casts = [

        'total_bayar' => 'decimal:2',

    ];



    /**

     * The relationships that should always be loaded.

     *

     * @var array

     */

    // protected $with = [

    //     'user',

    //     'ticket',

    //     'event',

    // ];



    /**

     * Accessor for payment_proof_path to get full path.

     */

    protected function paymentProofPath(): Attribute

    {

        return Attribute::make(

            get: fn ($value) => $value ? asset('storage/' . $value) : null,

        );

    }



    /**

     * Get the user that owns the registration.

     */

    public function user(): BelongsTo

    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ticket that was purchased.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the event for the registration.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
     * Get the e-tickets for the registration.
     */
    public function eTickets(): HasMany
    {
        return $this->hasMany(ETicket::class);
    }
}
