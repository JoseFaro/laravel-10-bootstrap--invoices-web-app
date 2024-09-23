<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Scopes\CompanyScope;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'contacts',
        'invoice_type',
        'name',
        'payment_type',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
