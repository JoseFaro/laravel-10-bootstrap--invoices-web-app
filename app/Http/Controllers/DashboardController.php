<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Service;
use App\Models\Site;
use App\Models\SiteService;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, $is_default = '')
    {
        $is_default = !!$is_default;

        $has_start_date_request = !!app('request')->input('start_date');
        $has_end_date_request = !!app('request')->input('end_date');

        $start_date = '';
        $end_date = '';

        if ($is_default) {
            $start_date = date("Y-01-01");
            $end_date = date("Y-12-31");
        } else if ($has_start_date_request && $has_end_date_request){
            $start_date = app('request')->input('start_date');
            $end_date = app('request')->input('end_date');
        }

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $activities = $this->prepareActivitiesQuery($request, $start_date, $end_date)->get();
        $expenses = $this->prepareExpensesQuery($request, $start_date, $end_date)->get();

        $utilities = $this->getUtilities($activities, $expenses, $request);

        $units = Unit::orderBy('name', 'asc')->get();
        $clients = Client::orderBy('name', 'asc')->get();

        $sites = 
            ($request->client_id ?
                Site::where('client_id', $request->client_id) :
                Site::query()
            )->orderBy('name', 'asc')->get();

        $services = Service::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $expenses_categories = ExpenseCategory::orderBy('name', 'asc')->get();

        $isRequestedFullYearWithDates = isFullYearWithDates($start_date, $end_date);
        $selectedYearForSearch = $isRequestedFullYearWithDates ? getYearFromStringDate($start_date) : '';

        return view('dashboard.index', [
            'clients' => $clients,
            'expenses_categories' => $expenses_categories,
            'services' => $services,
            'suppliers' => $suppliers,
            'sites' => $sites,
            'units' => $units,
            'utilities' => $utilities,

            'isRequestedFullYearWithDates' => $isRequestedFullYearWithDates,
            'selectedYearForSearch' => $selectedYearForSearch,
        ]);
    }

    private function prepareActivitiesQuery($request, $start_date, $end_date) 
    {
        $query = Activity::orderBy('date', 'desc');
        
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

        if ($start_date && $end_date) {
            $query->where(function($q) use ($start_date, $end_date){
                $q->where(function($q2) use ($start_date, $end_date){
                    $q2->where('payment_date', '>=', $start_date);
                    $q2->where('payment_date', '<=', $end_date);
                });

                $q->orWhereNull('payment_date');
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

        return $query;
    }   

    private function prepareExpensesQuery($request, $start_date, $end_date) 
    {
        $query = Expense::orderBy('date', 'desc');

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

        if ($start_date && $end_date) {
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

        return $query;
    }

    private function getUtilities($activities, $expenses, $request) 
    {
        $utilities = [
            'dates' => [],
            'totalCost' => 0,
            'totalBilled' => 0,
            'totalNotBillable' => 0,
            'totalBillableNotBilled' => 0,
            'totalBilledAndPaid' => 0,
            'totalBilledAndNotPaid' => 0,
            'totalNotBillableAndPaid' => 0,
            'totalNotBillableAndNotPaid' => 0,
            'totalExpenses' => 0,
            'totalExpensesBilled' => 0,
            'totalExpensesBilledAndPaid' => 0,
        ];
        $totalCost = 0;

        $today = date('Y-m-d', strtotime('now'));
        $currentDateIsNotInRange = !($today >= $request->start_date && $today <= $request->end_date);

        foreach($activities as $activity) {
            if (!$activity->payment_date && $currentDateIsNotInRange) {
                continue;
            }

            $applied_date = $activity->payment_date ? $activity->payment_date : $today;
            $monthParths = explode('-', $applied_date);
            $is_daily_report = $request->report_type == 'daily';

            $date = $is_daily_report ? $applied_date : $monthParths[0] . '-' . $monthParths[1] . '-00';

            $utilities = $this->initialize_values($date, $utilities);

            // ingresos totales
            $utilities['dates'][$date]['totalCost'] += $activity->cost;
            $utilities['totalCost'] += $activity->cost;
            
            // ingresos facturados
            $is_billed = $activity->billed;
            if ($is_billed) {
                $utilities['dates'][$date]['totalBilled'] += $activity->cost;
                $utilities['totalBilled'] += $activity->cost;
            }
            
            // ingresos no facturables
            $is_not_billable = !$activity->billable;
            if ($is_not_billable) {
                $utilities['dates'][$date]['totalNotBillable'] += $activity->cost;
                $utilities['totalNotBillable'] += $activity->cost;
            }

            // ingresos facturables no facturados
            $is_billable_and_not_billed = $activity->billable && !$activity->billed;
            if ($is_billable_and_not_billed) {
                $utilities['dates'][$date]['totalBillableNotBilled'] += $activity->cost;
                $utilities['totalBillableNotBilled'] += $activity->cost;
            }

            // ingresos facturados y pagados
            $is_billed_and_paid = $activity->billed && $activity->paid;
            if ($is_billed_and_paid) {
                $utilities['dates'][$date]['totalBilledAndPaid'] += $activity->cost;
                $utilities['totalBilledAndPaid'] += $activity->cost;
            }

            // ingresos facturados y no pagados
            $is_billed_and_not_paid = $activity->billed && !$activity->paid;
            if ($is_billed_and_not_paid) {
                $utilities['dates'][$date]['totalBilledAndNotPaid'] += $activity->cost;
                $utilities['totalBilledAndNotPaid'] += $activity->cost;
            }         

            // ingresos no facturables y pagados
            $is_not_billable_and_paid = !$activity->billable && $activity->paid;
            if ($is_not_billable_and_paid) {
                $utilities['dates'][$date]['totalNotBillableAndPaid'] += $activity->cost;
                $utilities['totalNotBillableAndPaid'] += $activity->cost;
            }

            // ingresos no facturables y no pagados
            $is_not_billable_and_not_paid = !$activity->billable && !$activity->paid;
            if ($is_not_billable_and_not_paid) {
                $utilities['dates'][$date]['totalNotBillableAndNotPaid'] += $activity->cost;
                $utilities['totalNotBillableAndNotPaid'] += $activity->cost;
            }
        }

        foreach($expenses as $expense) { 
            $monthParths = explode('-', $expense->date);
            $is_daily_report = $request->report_type == 'daily';

            $date = $is_daily_report ? $expense->date : $monthParths[0] . '-' . $monthParths[1] . '-00';

            $utilities = $this->initialize_values($date, $utilities);

            // gastos totales
            $utilities['dates'][$date]['totalExpenses'] += $expense->amount;
            $utilities['totalExpenses'] += $expense->amount;
    
            // gastos totales facturados
            $is_expense_billed = $expense->billed;
            if ($is_expense_billed) {
                $utilities['dates'][$date]['totalExpensesBilled'] += $expense->amount;
                $utilities['totalExpensesBilled'] += $expense->amount;
            }
        }

        ksort($utilities['dates']);

        return $utilities;
    }  
    
    private function initialize_values($date, $utilities) 
    {
        $utilities['dates'][$date]['year'] = isset($utilities['dates'][$date]['year']) ? $utilities['dates'][$date]['year'] : getYearFromStringDate($date);
        $utilities['dates'][$date]['month'] = isset($utilities['dates'][$date]['month']) ? $utilities['dates'][$date]['month'] : getMonthFromStringDate($date);
        $utilities['dates'][$date]['day'] = isset($utilities['dates'][$date]['day']) ? $utilities['dates'][$date]['day'] : getDayFromStringDate($date);

        $utilities['dates'][$date]['totalCost'] = isset($utilities['dates'][$date]['totalCost']) ? $utilities['dates'][$date]['totalCost'] : 0;
        $utilities['dates'][$date]['totalBilled'] = isset($utilities['dates'][$date]['totalBilled']) ? $utilities['dates'][$date]['totalBilled'] : 0;
        $utilities['dates'][$date]['totalNotBillable'] = isset($utilities['dates'][$date]['totalNotBillable']) ? $utilities['dates'][$date]['totalNotBillable'] : 0;

        $utilities['dates'][$date]['totalBillableNotBilled'] = isset($utilities['dates'][$date]['totalBillableNotBilled']) ? $utilities['dates'][$date]['totalBillableNotBilled'] : 0;

        $utilities['dates'][$date]['totalBilledAndPaid'] = isset($utilities['dates'][$date]['totalBilledAndPaid']) ? $utilities['dates'][$date]['totalBilledAndPaid'] : 0;
        $utilities['dates'][$date]['totalBilledAndNotPaid'] = isset($utilities['dates'][$date]['totalBilledAndNotPaid']) ? $utilities['dates'][$date]['totalBilledAndNotPaid'] : 0;

        $utilities['dates'][$date]['totalNotBillableAndPaid'] = isset($utilities['dates'][$date]['totalNotBillableAndPaid']) ? $utilities['dates'][$date]['totalNotBillableAndPaid'] : 0;
        $utilities['dates'][$date]['totalNotBillableAndNotPaid'] = isset($utilities['dates'][$date]['totalNotBillableAndNotPaid']) ? $utilities['dates'][$date]['totalNotBillableAndNotPaid'] : 0;

        $utilities['dates'][$date]['totalExpenses'] = isset($utilities['dates'][$date]['totalExpenses']) ? $utilities['dates'][$date]['totalExpenses'] : 0;
        $utilities['dates'][$date]['totalExpensesBilled'] = isset($utilities['dates'][$date]['totalExpensesBilled']) ? $utilities['dates'][$date]['totalExpensesBilled'] : 0;
        $utilities['dates'][$date]['totalExpensesBilledAndPaid'] = isset($utilities['dates'][$date]['totalExpensesBilledAndPaid']) ? $utilities['dates'][$date]['totalExpensesBilledAndPaid'] : 0;

        return $utilities;
    }
}
