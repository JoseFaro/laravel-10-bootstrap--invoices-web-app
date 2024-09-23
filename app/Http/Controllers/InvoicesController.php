<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceActivity;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'invoice_activities' => function ($q) {
            $q->orderBy('activity_id', 'desc');
        }, 'invoice_activities.activity']);

        // ordering section
        $orderBy = $request->orderBy;
        if($orderBy) {
            $orderDirection = $request->orderDirection == 'asc' ? 'asc' : 'desc';
            
            $orderFieldsAvailable = [
                'bill_code',
                'billed_date',
                'subtotal',
                'iva',
                'retained_iva',
                'isr',
                'total',
                'created_at',
                'updated_at',
            ];

            if (in_array($orderBy, $orderFieldsAvailable)) {
                $query->orderBy($orderBy, $orderDirection);
            }

            if ($orderBy == 'client_id') {
                $query->orderBy(
                    Client::select('name')
                        ->whereColumn('client_id', 'clients.id')
                        ->limit(1),
                    $orderDirection,
                );
            }
        } 
        
        // second default ordering
        if (!$orderBy || $orderBy != 'billed_date') {
            $query->orderBy('billed_date', 'desc');
        }

        $invoices = $query->paginate(config('constants.pagination'))->withQueryString();

        return view('invoices.index', [
            'invoices' => $invoices
        ]);
    }

    public function create()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        $client_id = old('client_id');
        $requested_activities_id = old('activities', []);
        
        if (count($requested_activities_id)) {
            $activities = Activity::
                getActivitiesForInvoice($client_id)
                ->map(function ($activity) use ($requested_activities_id) {
                    $activity->checked = in_array($activity->id, $requested_activities_id);
                    return $activity;
                });
        } else {
            $activities = $client_id ? Activity::getActivitiesForInvoice($client_id) : [];
        }

        return view('invoices.create', [
            'activities' => $activities,
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'bill_code' => 'required',
            'billed_date' => 'required|date',
            'client_id' => 'required',
            'isr' => 'required|numeric',
            'iva' => 'required|numeric',
            'paid' => $request->payment_date ? 'required|accepted' : 'nullable',
            'payment_date' => $request->paid ? 'required|date' : 'nullable',
            'retained_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $invoice = new Invoice();
        $invoice->fill($request->all());
        $invoice->paid = $request->paid ? 1 : 0;

        $invoice->company_id = Auth::user()->company_id;
        $invoice->save();

        // saves invoice activities relations and information
        if (isset($request->activities)) {
            foreach($request->activities as $activity_id) {
                $invoice_activity = new InvoiceActivity();
                $invoice_activity->activity_id = $activity_id;
                $invoice_activity->invoice_id = $invoice->id;

                $invoice_activity->company_id = Auth::user()->company_id;
                $invoice_activity->save();

                $activity = Activity::find($activity_id);
                if ($activity) {
                    $activity->billed = 1;
                    $activity->paid = $invoice->paid ? 1 : 0;
                    $activity->payment_date = $invoice->payment_date;
                    $activity->save();
                }
            }
        }
        // end invoice activities saves

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        //
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::where('id', $invoice->client_id)->get();

        $client_id = $invoice->client_id;
        $invoice_activities_ids = $invoice->activities_ids() ;
        $has_invoice_activities = !!count($invoice_activities_ids);
        $requested_activities_ids = old('activities', $invoice_activities_ids);

        $activities = Activity::
            getActivitiesForInvoice($client_id, $invoice_activities_ids, $has_invoice_activities)
            ->map(function ($activity) use ($requested_activities_ids) {
                $activity->checked = in_array($activity->id, $requested_activities_ids);
                return $activity;
            });

        if (!session()->get('errors')) {
            session()->put('redirect_on_update_invoice', url()->previous());
        }

        return view('invoices.edit', [
            'activities' => $activities,
            'clients' => $clients,
            'invoice' => $invoice,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'bill_code' => 'required',
            'billed_date' => 'required|date',
            'client_id' => 'required',
            'isr' => 'required|numeric',
            'iva' => 'required|numeric',
            'paid' => $request->payment_date ? 'required|accepted' : 'nullable',
            'payment_date' => $request->paid ? 'required|date' : 'nullable',
            'retained_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $invoice->fill($request->all());
        $invoice->paid = $request->paid ? 1 : 0;
        $invoice->save();

        // updates invoice activities relations and information
        $invoice_activities_ids = $invoice->activities_ids();
        $requested_activities_id = isset($request->activities) ? $request->activities : [];

        $invoice_activities_ids_to_remove = Arr::where($invoice_activities_ids, function ($value) use ($requested_activities_id) {
            return !in_array($value, $requested_activities_id);
        });

        $invoice_activities_ids_to_add = Arr::where($requested_activities_id, function ($value) use ($invoice_activities_ids) {
            return !in_array($value, $invoice_activities_ids);
        });

        foreach($invoice_activities_ids_to_add as $activity_id) {
            $invoice_activity = new InvoiceActivity();
            $invoice_activity->activity_id = $activity_id;
            $invoice_activity->invoice_id = $invoice->id;

            $invoice_activity->company_id = Auth::user()->company_id;
            $invoice_activity->save();

            $activity = Activity::find($activity_id);
            if ($activity) {
                $activity->billed = 1;
                $activity->save();
            }
        }

        foreach($invoice_activities_ids_to_remove as $activity_id) {
            $invoice_activity = InvoiceActivity::where('invoice_id', $invoice->id)->where('activity_id', $activity_id)->delete();

            $activity = Activity::find($activity_id);
            if ($activity) {
                $activity->billed = 0;
                $activity->paid = 0;
                $activity->payment_date = NULL;
                $activity->save();
            }
        }

        foreach($invoice->invoice_activities()->get() as $invoice_activity) {
            $activity = Activity::find($invoice_activity->activity_id);
            if ($activity) {
                $activity->paid = $invoice->paid ? 1 : 0;
                $activity->payment_date = $invoice->payment_date;
                $activity->save();
            }
        }
        // end invoice activities updates
        
        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_invoice'));
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        $invoice->delete();

        if ($invoice->invoice_activities) {
            foreach($invoice->invoice_activities as $invoice_activity) {
                if ($invoice_activity->activity) {
                    $invoice_activity->activity->billed = 0;
                    $invoice_activity->activity->paid = 0;
                    $invoice_activity->activity->save();
                }

                $invoice_activity->delete();
            }
        }

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
