<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Expense;
use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expenses_categories';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
