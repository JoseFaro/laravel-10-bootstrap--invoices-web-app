<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\Company;
use App\Models\ExpenseCategory;
use App\Models\Scopes\CompanyScope;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'billed',
        'date',
        'description',
        'expenses_category_id',
        'supplier_id',
        'unit_id',
    ];

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function expenses_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
