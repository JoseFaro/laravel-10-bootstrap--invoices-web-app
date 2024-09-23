<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Company;
use App\Models\InvoiceActivity;
use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_code',
        'billed_date',
        'client_id',
        'isr',
        'iva',
        'paid',
        'payment_date',
        'retained_iva',
        'subtotal',
        'total',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice_activities()
    {
        return $this->hasMany(InvoiceActivity::class);
    }

    ///////////////

    public function activities_ids()
    {
        $activities_ids = [];

        foreach($this->invoice_activities as $invoice_activity){
            $activities_ids[] = $invoice_activity->activity_id;
        }

        return $activities_ids;
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
