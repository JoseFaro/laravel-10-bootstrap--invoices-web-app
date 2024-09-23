<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Auth;
use Illuminate\Http\Request;

class ExpensesCategoriesController extends Controller
{
    public function index()
    {
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        return view('expenses-categories.index', ['expenses_categories' => $expenses_categories]);
    }

    public function create()
    {
        return view('expenses-categories.create');
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);

        $expenses_category = new ExpenseCategory();
        $expenses_category->fill($request->all());

        $expenses_category->company_id = Auth::user()->company_id;
        $expenses_category->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('expenses-categories.index');
    }

    public function show(ExpenseCategory $expenses_category)
    {
    }

    public function edit(ExpenseCategory $expenses_category)
    {
        if (!session()->get('errors')) {
            session()->put('redirect_on_update_expense_category', url()->previous());
        }

        return view('expenses-categories.edit', ['expenses_category' => $expenses_category]);
    }

    public function update(Request $request, ExpenseCategory $expenses_category)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);

        $expenses_category->fill($request->all());
        $expenses_category->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_expense_category'));
    }

    public function destroy(Request $request, ExpenseCategory $expenses_category)
    {
        $expenses_category->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
