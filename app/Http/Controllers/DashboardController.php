<?php

namespace App\Http\Controllers;

use App\Models\VehicleInformation;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function countRegisteredSpots()
    {
        $registeredSpots = VehicleInformation::count();
        return response()->json(['registered_spots' => $registeredSpots]);
    }

    public function countUnregisteredSpots()
    {
        $totalAreas = Area::sum('total');
        $registeredSpots = VehicleInformation::count();
        $unregisteredSpots = $totalAreas - $registeredSpots;

        return response()->json(['unregistered_spots' => $unregisteredSpots]);
    }

    public function getPieChartData()
    {
        $carsInLot = VehicleInformation::where('status', 'active')->count();
        $registeredSpots = VehicleInformation::count();
        $carsOutLot = $registeredSpots - $carsInLot;

        return response()->json([
            'cars_in_lot' => $carsInLot,
            'cars_out_lot' => $carsOutLot
        ]);
    }

    public function getBarChartData()
    {
        $today = Carbon::today();

        $carsIn = DB::table('vehicle_logs')
            ->where('status', 'active')
            ->whereDate('created_at', $today)
            ->count();

        $carsOut = DB::table('vehicle_logs')
            ->where('status', 'inactive')
            ->whereDate('created_at', $today)
            ->count();

        return response()->json([
            'cars_in' => $carsIn,
            'cars_out' => $carsOut
        ]);
     }

     public function countCarsIn()
    {
        $carsIn = DB::table('vehicle_logs')
            ->where('status', 'active')
            ->count();

        return response()->json(['cars_in' => $carsIn]);
    }

    public function countCarsOut()
    {
        $carsOut = DB::table('vehicle_logs')
            ->where('status', 'inactive')
            ->count();

        return response()->json(['cars_out' => $carsOut]);
    }
}
