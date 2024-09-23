<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Scopes\CompanyScope;
use App\Models\SiteService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function clientServices()
    {
        return $this->hasMany(SiteService::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
