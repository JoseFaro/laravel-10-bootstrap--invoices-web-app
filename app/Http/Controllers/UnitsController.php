<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name', 'asc')->paginate(config('constants.pagination'));
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        return view('units.index', [
            'expenses_categories' => $expenses_categories,
            'units' => $units
        ]);
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'consume_ratio' => 'nullable|numeric',
            'name' => 'required',
        ]);

        $unit = new Unit();
        $unit->fill($request->all());

        $unit->company_id = Auth::user()->company_id;
        $unit->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('units.index');
    }

    public function show(Unit $unit)
    {
        //
    }

    public function edit(Unit $unit)
    {
        if (!session()->get('errors')) {
            session()->put('redirect_on_update_unit', url()->previous());
        }

        return view('units.edit', ['unit' => $unit]);
    }

    public function update(Request $request, Unit $unit)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'consume_ratio' => 'nullable|numeric',
            'name' => 'required',
        ]);

        $unit->fill($request->all());
        $unit->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_unit'));
    }

    public function destroy(Request $request, Unit $unit)
    {
        $unit->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
