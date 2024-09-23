<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Auth;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return view('suppliers.index', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->fill($request->all());

        $supplier->company_id = Auth::user()->company_id;
        $supplier->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('suppliers.index');
    }

    public function show(Supplier $supplier)
    {
        //
    }
    
    public function edit(Supplier $supplier)
    {
        if (!session()->get('errors')) {
            session()->put('redirect_on_update_supplier', url()->previous());
        }

        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);

        $supplier->fill($request->all());
        $supplier->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_supplier'));
    }

    public function destroy(Request $request, Supplier $supplier)
    {
        $supplier->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
