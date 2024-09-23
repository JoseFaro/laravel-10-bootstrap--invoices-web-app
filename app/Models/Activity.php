<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Company;
use App\Models\Expense;
use App\Models\InvoiceActivity;
use App\Models\Scopes\CompanyScope;
use App\Models\Site;
use App\Models\SiteService;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'cost',
        'date',
        'expense_id',
        'site_id',
        'site_service_id',
        'unit_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function invoice_activity()
    {
        return $this->hasOne(InvoiceActivity::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function site_service()
    {
        return $this->belongsTo(SiteService::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    ///////////////

    public static function getActivitiesForInvoice($client_id, $invoice_activities_ids = [], $has_invoice_activities = false)
    {
        $activities = 
            Activity::orderBy('date', 'desc')
                ->where('client_id', $client_id)
                ->where('billable', 1)
                ->where(function($q) use ($has_invoice_activities, $invoice_activities_ids) {
                    $q->where('billed', 0);

                    if ($has_invoice_activities) {
                        $q->orWhere(function($q2) use ($has_invoice_activities, $invoice_activities_ids) {
                            $q2->where('billed', 1);
                            $q2->whereIn('id', $invoice_activities_ids);
                        });
                    }
                })
                ->get();
        
        $activities->load('site', 'site_service', 'site_service.service', 'unit');

        return $activities;
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
