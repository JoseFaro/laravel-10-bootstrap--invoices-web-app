<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceActivity extends Model
{
    use HasFactory;

    protected $table = 'invoice_activities';
    public $timestamps = false;
    protected $fillable = [
        'activity_id',
        'invoice_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
