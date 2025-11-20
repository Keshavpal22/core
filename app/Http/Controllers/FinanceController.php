<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\XpricingHelper;
use App\Models\X_Branch;
use App\Models\XlFinancier;
use App\Models\X_Location;
use App\Models\Booking;
use Auth;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:can_manage_finance');
    }

    /* =============================================================
       MAIN PAGE – DataTable + Filters
       ============================================================= */
    public function index(Request $request)
    {
        $branches    = X_Branch::select('id', 'name')->orderBy('name')->get();
        $financiers  = XlFinancier::select('id', 'name')->orderBy('name')->get();
        $consultants = collect(XpricingHelper::selectfsc())->sortBy('name');
        $models      = DB::table('xcelr8_booking_master')
            ->distinct()
            ->whereNotNull('model')
            ->orderBy('model')
            ->pluck('model');

        return view('finance.index', compact(
            'branches',
            'financiers',
            'consultants',
            'models'
        ));
    }

    /* =============================================================
       AJAX – DataTable with ALL filters
       ============================================================= */
    public function list(Request $request)
    {
        $query = DB::table('xcelr8_booking_master as bookings')
            ->select(
                'bookings.id as booking_no',
                'bookings.created_at',
                'bookings.booking_date',
                'bookings.b_type',
                'bookings.branch_id',
                'bookings.location_id',
                'bookings.location_other',
                'bookings.segment_id',
                'bookings.model',
                'bookings.variant',
                'bookings.color',
                'bookings.consultant as fsc',
                'bookings.fin_mode',
                'bookings.financier',
                'bookings.loan_status',
                'xf.status as finance_status'
            )
            ->leftJoin('xcelr8_finance as xf', 'bookings.id', '=', 'xf.bid')
            ->where('bookings.status', '!=', '2')
            ->where(function ($q) {
                $q->whereNull('xf.fin_mode')
                    ->orWhere('xf.fin_mode', 'In-house');
            });

        // === FILTERS ===
        if ($request->filled('status')) {
            $query->where('xf.status', $request->status == 'pending' ? 1 : 2);
        } else {
            $query->where('xf.status', 1); // default pending
        }

        if ($request->filled('branch_id')) {
            $query->where('bookings.branch_id', $request->branch_id);
        }
        if ($request->filled('financier_id')) {
            $query->where('bookings.financier', $request->financier_id);
        }
        if ($request->filled('fsc_id')) {
            $query->where('bookings.consultant', $request->fsc_id);
        }
        if ($request->filled('model')) {
            $query->where('bookings.model', $request->model);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('bookings.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('bookings.created_at', '<=', $request->date_to);
        }

        $query->orderBy('bookings.id', 'DESC');
        $bookings = $query->get();

        // === LOOKUP ===
        $segments        = XpricingHelper::getSegments();
        $saleConsultants = collect(XpricingHelper::selectfsc())->keyBy('id');
        $financiers      = XlFinancier::select('id', 'name')->get()->keyBy('id');
        $branches        = X_Branch::select('id', 'name')->get()->keyBy('id');
        $locations       = X_Location::select('id', 'name')->get()->keyBy('id');

        // === TRANSFORM ===
$data = $bookings->map(function ($t) use ($segments, $saleConsultants, $financiers, $branches, $locations) {
    return [
        'DT_RowIndex'  => null, // ← ADD THIS
        'booking_no'   => $t->booking_no,
        'created_at'   => Carbon::parse($t->created_at)->format('d-M-Y'),
        'booking_date' => Carbon::parse($t->booking_date)->format('d-M-Y'),
        'b_type'       => $t->b_type ?? 'N/A',
        'branch_id'    => $branches->get($t->branch_id)->name ?? 'N/A',
        'location'     => $t->location_id > 0
            ? ($locations->get($t->location_id)->name ?? 'N/A')
            : ($t->location_other ?? 'N/A'),
        'segment_id'   => $segments[$t->segment_id]['name'] ?? 'N/A',
        'model'        => $t->model ?? 'N/A',
        'variant'      => $t->variant ?? 'N/A',
        'color'        => $t->color ?? 'N/A',
        'fsc'          => $saleConsultants->get($t->fsc)->name ?? 'N/A',
        'fin_mode'     => $t->fin_mode ?? 'N/A',
        'financier'    => $financiers->get($t->financier)->name ?? 'N/A',
        'loan_status'  => $t->loan_status ?? 'N/A', // ← FIXED: removed extra =>
        'action'       => '<div class="table-actions text-center">
                            <a href="' . route('finance.edit', $t->booking_no) . '" title="Edit">
                                <i class="ik ik-edit-2 f-16 text-green"></i>
                            </a>
                          </div>',
    ];
});

        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    // -----------------------------------------------------------------
    // All other methods (edit, update, payout, etc.) remain **unchanged**
    // -----------------------------------------------------------------
    public function edit($id)
    { /* your existing code */
    }
    public function update(Request $request, $id)
    { /* your existing code */
    }
    public function notInterested()
    { /* ... */
    }
    public function notInterestedList(Request $request)
    { /* ... */
    }
    public function payout()
    { /* ... */
    }
    public function payoutList(Request $request)
    { /* ... */
    }
    public function payoutedit($id)
    { /* ... */
    }
    public function payoutupdate(Request $request, $id)
    { /* ... */
    }
    public function payoutcompleted()
    { /* ... */
    }
    public function payoutcompletedList(Request $request)
    { /* ... */
    }
    public function retail()
    { /* ... */
    }
    public function retailList(Request $request)
    { /* ... */
    }
    public function retailedit($id)
    { /* ... */
    }
    public function retailed()
    { /* ... */
    }
    public function retailedList(Request $request)
    { /* ... */
    }
    public function view($id)
    { /* ... */
    }
}
