<?php

namespace App\Http\Controllers;

use App\Models\Keshav;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class KeshavController extends Controller
{
    // 1. Show Create Form
    public function create()
    {
        return view('keshav.create'); // your create form
    }

    // 2. Store New User
    public function store(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:50',
            'last_name'        => 'required|string|max:50',
            'email'            => [
                'required',
                'email',
                'regex:/^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com)$/i',
                'unique:keshav,email'
            ],
            'gender'           => 'required|in:male,female',
            'phone'            => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
                'unique:keshav,phone'
            ],
            'address'          => 'required|string|max:100',
            'occupation_field' => 'required|in:engineering,doctor,teacher,business,other',
            'experience'       => 'required|integer|min:0|max:60',
            'mode_of_transfer' => 'required|in:car,bike,bus,metro,cycle,walking',
        ]);

        Keshav::create([
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'gender'           => $request->gender === 'male' ? 1 : 0,
            'phone'            => (int) $request->phone,
            'address'          => $request->address,
            'occupation_field' => $request->occupation_field,
            'experience'       => (int) $request->experience,
            'mode_of_transfer' => $request->mode_of_transfer,
        ]);

        return redirect()->route('newuser.create')
            ->with('success', 'User registered successfully!');
    }

    // 3. List All Users (Main Page)
    public function index()
    {
        return view('keshav.index');
    }


    public function list(Request $request)
    {
        $query = DB::table('keshav')
            ->select(
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'gender',
                'address',
                'occupation_field',
                'experience',
                'mode_of_transfer',
                'created_at'
            );

        // Add any future filters here if needed (example: search, status, etc.)
        // Currently no dropdown filters like finance module, so keeping it simple

        $query->orderBy('id', 'DESC');

        $users = $query->get();

        // Transform & enrich data exactly like your desired format
        $data = $users->map(function ($user) {
            $fullName = trim($user->first_name . ' ' . $user->last_name);

            return [
                'DT_RowIndex'     => '', // will be handled by DataTables addIndexColumn
                'full_name'       => $fullName ?: 'N/A',
                'email'           => $user->email,
                'phone'           => $user->phone,
                'gender'          => $user->gender == 1 ? 'Male' : 'Female',
                'occupation_field' => ucfirst($user->occupation_field),
                'experience'      => $user->experience . ' years',
                'mode_of_transfer' => ucwords(str_replace('_', ' ', $user->mode_of_transfer)),
                'created_at'      => \Carbon\Carbon::parse($user->created_at)->format('d M, Y'),
                'action' => '<div class="action-btns">
                <a href="' . route('users.show', $user->id) . '" class="btn btn-info btn-sm" title="View Details">
        <i class="ik ik-eye"></i>
    </a>
    <a href="' . route('users.edit', $user->id) . '" class="btn btn-success btn-sm" title="Edit User">
        <i class="ik ik-edit-2"></i>
    </a>

</div>',

            ];
        });

        return DataTables::of($data)
            ->addIndexColumn()              // Auto S.No
            ->rawColumns(['action'])        // Important: render HTML buttons
            ->make(true);
    }


    // 3.1 AJAX Data for DataTable
    // public function list()
    // {
    //     $users = Keshav::select(['id', 'first_name', 'last_name', 'email', 'phone', 'gender', 'occupation_field', 'experience', 'mode_of_transfer', 'created_at']);

    //     return DataTables::of($users)
    //         ->addColumn('DT_RowIndex', function () {
    //             return '';
    //         })
    //         ->addColumn('full_name', fn($user) => $user->first_name . ' ' . $user->last_name)
    //         ->editColumn('gender', fn($user) => $user->gender == 1 ? 'Male' : 'Female')
    //         ->editColumn('created_at', fn($user) => $user->created_at->format('d M, Y'))
    //         ->addColumn('action', fn($user) => $user->id) // we'll render buttons in Blade
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

    // 4. View Single User
    public function show($id)
    {
        $user = Keshav::findOrFail($id);
        return view('keshav.show', compact('user'));
    }

    // 5. Edit Form
    public function edit($id)
    {
        $user = Keshav::findOrFail($id);
        return view('keshav.edit', compact('user'));
    }

    // 6. Update User – FIXED: No more regex error + unique ignore
    public function update(Request $request, $id)
    {
        $user = Keshav::findOrFail($id);

        $request->validate([
            'first_name'       => 'required|string|max:50',
            'last_name'        => 'required|string|max:50',
            'email'            => [
                'required',
                'email',
                'regex:/^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com)$/i',
                Rule::unique('keshav', 'email')->ignore($id),
            ],
            'gender'           => 'required|in:male,female',
            'phone'            => [
                'required',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('keshav', 'phone')->ignore($id),
            ],
            'address'          => 'required|string|max:100',
            'occupation_field' => 'required|in:engineering,doctor,teacher,business,other',
            'experience'       => 'required|integer|min:0|max:60',
            'mode_of_transfer' => 'required|in:car,bike,bus,metro,cycle,walking',
        ]);

        $user->update([
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'gender'           => $request->gender === 'male' ? 1 : 0,
            'phone'            => (int) $request->phone,
            'address'          => $request->address,
            'occupation_field' => $request->occupation_field,
            'experience'       => (int) $request->experience,
            'mode_of_transfer' => $request->mode_of_transfer,
        ]);

        return redirect()->route('keshav.index')
            ->with('success', 'User updated successfully!');
    }

    // // 7. Delete User
    public function destroy($id)
    {
        Keshav::findOrFail($id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
