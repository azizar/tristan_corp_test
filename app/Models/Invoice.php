<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        "customer_id",
        "due_date",
        "subject",
        "current_payment",
        "tax_percent"
    ];

    /**
     * Get the customer associated with the Invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'invoice_items', 'invoice_id', 'item_id')->withPivot('quantity', 'amount_total', 'description', 'price');
    }

    // public static function boot()
    // {
    //     parent::boot();
    //     self::deleting(function ($invoice) { // before delete() method call this
    //         $invoice->items()->each(function ($item) {
    //             $item->delete(); // <-- direct deletion
    //         });
    //         // do the rest of the cleanup...
    //     });
    // }
}
