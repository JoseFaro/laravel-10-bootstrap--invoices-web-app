<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Scopes\CompanyScope;
use App\Models\Services;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteService extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost',
        'service_id',
        'site_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
