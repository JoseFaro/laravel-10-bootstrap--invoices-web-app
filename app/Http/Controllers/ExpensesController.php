<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Supplier;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['unit', 'activity', 'supplier', 'expenses_category'])->orderBy('date', 'desc')->paginate(config('constants.pagination'));
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();

        return view('expenses.index', [
            'expenses' => $expenses,
            'expenses_categories' => $expenses_categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function search(Request $request)
    {
        $query = Expense::with(['unit', 'activity', 'supplier', 'expenses_category']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->orWhere('id', $search);
                $q->orWhere('amount', $search);
                $q->orWhere('description', 'like',  '%'.$search.'%');
                $q->orWhere('date', 'like', '%'.$search.'%');
            });
        }
        
        if ($request->unit_id) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->expenses_category_id) {
            $query->where('expenses_category_id', $request->expenses_category_id);
        }

        if ($request->start_date && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            
            $query->where(function($q) use ($start_date, $end_date){
                $q->orWhere(function($q2) use ($start_date, $end_date){
                    $q2->where('date', '>=', $start_date);
                    $q2->where('date', '<=', $end_date);
                });
            });
            
        }

        if ($request->billed && $request->billed != '') {
            $query->where('billed', $request->billed == 1 ? 1 : 0);
        }

        // ordering section
        $orderBy = $request->orderBy;
        if($orderBy) {
            $orderDirection = $request->orderDirection == 'asc' ? 'asc' : 'desc';
            
            $orderFieldsAvailable = [
                'date',
                'amount',
                'billed',
                'created_at',
                'updated_at',
            ];

            if (in_array($orderBy, $orderFieldsAvailable)) {
                $query->orderBy($orderBy, $orderDirection);
            }

            if ($orderBy == 'unit_id') {
                $query->orderBy(
                    Unit::select('name')
                        ->whereColumn('unit_id', 'units.id')
                        ->limit(1),
                    $orderDirection,
                );
            }

            if ($orderBy == 'supplier_id') {
                $query->orderBy(
                    Supplier::select('name')
                        ->whereColumn('supplier_id', 'suppliers.id')
                        ->limit(1),
                    $orderDirection,
                );
            }

            if ($orderBy == 'expenses_category_id') {
                $query->orderBy(
                    ExpenseCategory::select('name')
                        ->whereColumn('expenses_category_id', 'expenses_categories.id')
                        ->limit(1),
                    $orderDirection,
                );
            }
        } 
        
        // second default ordering
        if (!$orderBy || $orderBy != 'date') {
            $query->orderBy('date', 'desc');
        }

        $expenses = $query->paginate(config('constants.pagination'))->withQueryString();

        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();

        return view('expenses.index', [
            'expenses' => $expenses,
            'expenses_categories' => $expenses_categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function create()
    {
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();

        return view('expenses.create', [
            'expenses_categories' => $expenses_categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');
        
        $validator = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'required',
            'expenses_category_id' => 'required',
            'supplier_id' => 'required',
            'unit_id' => 'required',
        ]);

        $expense = new Expense();
        $expense->fill($request->all());
        
        $expense->billed = $request->billed ? 1 : 0;
        
        $expense->company_id = Auth::user()->company_id;
        $expense->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('expenses.index');
    }

    public function show(Expense $expense)
    {
        //
    }

    public function edit(Expense $expense)
    {
        $units = Unit::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        if (!session()->get('errors')) {
            session()->put('redirect_on_update_expense', url()->previous());
        }

        return view('expenses.edit', [
            'expense' => $expense,
            'expenses_categories' => $expenses_categories,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'required',
            'expenses_category_id' => 'required',
            'supplier_id' => 'required',
            'unit_id' => 'required',
        ]);

        $expense->fill($request->all());

        $expense->billed = $request->billed ? 1 : 0;

        $expense->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_expense'));
    }

    public function destroy(Request $request, Expense $expense)
    {
        $expense->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
