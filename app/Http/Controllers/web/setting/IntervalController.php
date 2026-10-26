<?php

namespace App\Http\Controllers\web\setting;

use App\Http\Controllers\Controller;
use App\Models\Interval;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class IntervalController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //
        $form = array(
            'title' => 'Interval Recorder',
            'sub_title' => 'Pengaturan'
        );

        $interval = Interval::orderBy('key_id', 'DESC')->paginate(1);

        // var_dump($indikator); exit;

        return view('interval.index', compact('interval', 'form'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Interval $interval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        //
        $interval = Interval::find($id);
        $permission = Permission::get();

        $form = array(
            'title' => 'Edit Indikator Kerusakan',
            'sub_title' => 'Pengaturan'
        );

        return view('interval.edit', compact('interval', 'form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
       
        $Indikator = Interval::find($id);
        $Indikator->interval = $request->input('interval');
        $Indikator->updated_at = now();
        $Indikator->save();

        $notification = array(
            'message' => 'Roles Updated successfully!',
            'alert_type' => 'success',
        );
        return redirect()->route('interval.index')
            ->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interval $interval)
    {
        //
    }
}
