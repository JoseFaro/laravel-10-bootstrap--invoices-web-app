<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Site;
use App\Models\SiteService;
use App\Models\Supplier;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('date', 'desc')->paginate(config('constants.pagination'));
        $activities->load('unit', 'client', 'site', 'site_service.service', 'invoice_activity', 'expense');
        $clients = Client::orderBy('name', 'asc')->get();
        $services = Service::orderBy('name', 'asc')->get();
        $sites = Site::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();

        return view('activities.index', [
            'activities' => $activities,
            'clients' => $clients,
            'services' => $services,
            'sites' => $sites,
            'units' => $units,
        ]);
    }

    public function search(Request $request)
    {
        $query = Activity::with(['unit', 'client', 'site', 'site_service.service', 'invoice_activity', 'expense']);

        // filters section
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->orWhere('id', $search);
                $q->orWhere('cost', $search);
                $q->orWhere('date', 'like', '%'.$search.'%');
            });
        }
        
        if ($request->unit_id) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->site_id) {
            $query->where('site_id', $request->site_id);
        }

        if ($request->service_id) {
            $service_id = $request->service_id;
            $query->whereHas('site_service', function ($q) use ($service_id)  {
                $q->where('service_id', $service_id);
            });
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

        if ($request->billable && $request->billable != '') {
            $query->where('billable', $request->billable == 1 ? 1 : 0);
        }

        if ($request->billed && $request->billed != '') {
            $query->where('billed', $request->billed == 1 ? 1 : 0);
        }

        if ($request->paid && $request->paid != '') {
            $query->where('paid', $request->paid == 1 ? 1 : 0);
        }

        // ordering section
        $orderBy = $request->orderBy;
        if($orderBy) {
            $orderDirection = $request->orderDirection == 'asc' ? 'asc' : 'desc';

            $orderFieldsAvailable = [
                'cost',
                'date',
                'payment_date',
                'billable',
                'billed',
                'paid',
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

            if ($orderBy == 'client_id') {
                $query->orderBy(
                    Client::select('name')
                        ->whereColumn('client_id', 'clients.id')
                        ->limit(1),
                    $orderDirection,
                );
            }

            if ($orderBy == 'site_id') {
                $query->orderBy(
                    Site::select('name')
                        ->whereColumn('site_id', 'sites.id')
                        ->limit(1),
                    $orderDirection,
                );
            }
        } 
        
        // second default ordering
        if (!$orderBy || $orderBy != 'date') {
            $query->orderBy('date', 'desc');
        }

        $activities = $query->paginate(config('constants.pagination'))->withQueryString();
        $clients = Client::orderBy('name', 'asc')->get();
        $services = Service::orderBy('name', 'asc')->get();

        $sites = 
            ($request->client_id ?
                Site::where('client_id', $request->client_id) :
                Site::query()
            )->orderBy('name', 'asc')->get();

        $units = Unit::orderBy('name', 'asc')->get();

        return view('activities.index', [
            'activities' => $activities,
            'clients' => $clients,
            'services' => $services,
            'sites' => $sites,
            'units' => $units,
        ]);
    }

    public function create()
    {
        $units = Unit::orderBy('name', 'asc')->get();
        $clients = Client::orderBy('name', 'asc')->get();
        $sites = [];
        $site_services = [];

        $client_id = old('client_id');
        if ($client_id) {
            $sites = Site::
                        orderBy('name', 'asc')
                        ->where('client_id', $client_id)
                        ->get();
        }
            
        $site_id = old('site_id');
        if ($site_id) {
           $site_services = SiteService::
                            where('site_id', $site_id)
                            ->orderBy(
                                Service::select('name')
                                    ->whereColumn('service_id', 'services.id')
                                    ->limit(1)
                            )->get();
        }

        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        return view('activities.create', [
            'clients' => $clients,
            'expenses_categories' => $expenses_categories,
            'site_services' => $site_services,
            'sites' => $sites,
            'suppliers' => $suppliers,
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validatorRules = [
            'client_id' => 'required',
            'cost' => 'nullable|numeric',
            'date' => 'required|date',
            'paid' => $request->payment_date ? 'required|accepted' : 'nullable',
            'payment_date' => $request->paid ? 'required|date' : 'nullable',
            'site_id' => 'required',
            'site_service_id' => 'required',
            'unit_id' => 'required',
        ];

        if ($request->create_expense) {
            $validatorRules['expense__amount'] = 'required|numeric';
            $validatorRules['expense__expenses_category_id'] = 'required';
            $validatorRules['expense__description'] = 'required';
            $validatorRules['expense__supplier_id'] = 'required';
        }

        $validator = $request->validate($validatorRules);

        $activity = new Activity();
        $activity->fill($request->all());
        
        // status updates
        $activity->billable = $request->billable ? 1 : 0;

        if (!$request->billable) {
            $activity->paid = $request->paid ? 1 : 0;
            $activity->payment_date = $request->payment_date;
        }
        // end

        // expense add
        if ($request->create_expense) {
            $expense = new Expense();
   
            $expense->date = $request->date;
            $expense->unit_id = $request->unit_id;

            $expense->amount = $request->expense__amount;
            $expense->billed = $request->expense__billed ? 1 : 0;
            $expense->description = $request->expense__description;
            $expense->expenses_category_id = $request->expense__expenses_category_id;
            $expense->supplier_id = $request->expense__supplier_id;

            $expense->company_id = Auth::user()->company_id;
            $expense->save();

            $activity->expense_id = $expense->id;
        }
        // end

        $activity->company_id = Auth::user()->company_id;
        $activity->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('activities.index');
    }

    public function show(Activity $activity)
    {
        //
    }

    public function edit(Activity $activity)
    {
        $units = Unit::orderBy('name', 'asc')->get();
        $clients = Client::orderBy('name', 'asc')->get();
        $sites = [];
        $site_services = [];

        $client_id = old('client_id', $activity->client_id);
        if ($client_id) {
            $sites = Site::
                        orderBy('name', 'asc')
                        ->where('client_id', $client_id)
                        ->get();
        }
            
        $site_id = old('site_id', $activity->site_id);
        if ($site_id) {
           $site_services = SiteService::
                            where('site_id', $site_id)
                            ->orderBy(
                                Service::select('name')
                                    ->whereColumn('service_id', 'services.id')
                                    ->limit(1)
                            )->get();
        }

        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        if (!session()->get('errors')) {
            session()->put('redirect_on_update_activity', url()->previous());
        }

        return view('activities.edit', [
            'activity' => $activity,
            'clients' => $clients,
            'expenses_categories' => $expenses_categories,
            'units' => $units,
            'site_services' => $site_services,
            'sites' => $sites,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $request->session()->flash('form_fail_update', '1');

        $validatorRules = [
            'client_id' => 'required',
            'cost' => 'nullable|numeric',
            'date' => 'required|date',
            'paid' => $request->payment_date ? 'required|accepted' : 'nullable',
            'payment_date' => $request->paid ? 'required|date' : 'nullable',
            'site_id' => 'required',
            'site_service_id' => 'required',
            'unit_id' => 'required',
        ];

        if ($activity->expense || $request->create_expense) {
            $validatorRules['expense__amount'] = 'required|numeric';
            $validatorRules['expense__description'] = 'required';
            $validatorRules['expense__expenses_category_id'] = 'required';
            $validatorRules['expense__supplier_id'] = 'required';
        }

        $validator = $request->validate($validatorRules);

        $activity->fill($request->all());

        // status updates
        $hasInvoiceActivities = !!$activity->invoice_activity;
        if (!$hasInvoiceActivities) {
            $activity->billable = $request->billable ? 1 : 0;

            if (!$request->billable) {
                $activity->paid = $request->paid ? 1 : 0;
                $activity->payment_date = $request->payment_date;
            } else {
                $activity->paid = 0;
                $activity->payment_date = NULL;
            }
        }
        // end

        // expense update or add
        if ($activity->expense || $request->create_expense) {
            $expense = $activity->expense ? $activity->expense : new Expense();

            $expense->date = $request->date;
            $expense->unit_id = $request->unit_id;

            $expense->amount = $request->expense__amount;
            $expense->billed = $request->expense__billed ? 1 : 0;
            $expense->description = $request->expense__description;
            $expense->expenses_category_id = $request->expense__expenses_category_id;
            $expense->supplier_id = $request->expense__supplier_id;

            $expense->save();

            $activity->expense_id = $expense->id;
        }
        // end

        $activity->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_activity'));
    }

    public function destroy(Request $request, Activity $activity)
    {
        $activity->delete();

        $request->session()->flash('form_success_delete', '1');

        if ($activity->expense) {
            $activity->expense->delete();

            if ($activity->invoice_activity) {
                $activity->invoice_activity->delete();
            }
        }

        return redirect()->back();
    }

    ///////////

    public function getDataByClient($client_id = '') {
        $client = Client::find($client_id);

        $activities = $client ? Activity::getActivitiesForInvoice($client_id) : false;

        return [
            'activities' => $activities ? $activities : [],
        ];
    }
}
