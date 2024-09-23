<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\InvoiceActivity;
use App\Models\Service;
use App\Models\Site;
use App\Models\SiteService;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function expenses_categories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoice_activities()
    {
        return $this->hasMany(InvoiceActivity::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function clientServices()
    {
        return $this->hasMany(SiteService::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
