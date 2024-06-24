<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleLog;

class VehicleLogController extends Controller
{
     // Hiển thị danh sách các lịch sử
     public function index()
     {
         $vehicleLogs = VehicleLog::orderBy('created_at', 'desc')->paginate(10);
         return view('vehicleLogs.index', compact('vehicleLogs'))->with('i', (request()->input('page', 1) - 1) *10);
     }

      // Tìm kiếm
 public function search(Request $request)
 {
     $search = $request->input('search');
     $vehicleLogs = VehicleLog::orderBy('created_at', 'desc')
         ->where('licensePlate', 'like', '%' . $search . '%') // Sử dụng điều kiện like để tìm kiếm không phân biệt chữ hoa chữ thường
         ->paginate(10); // Phân trang với 10 mục trên mỗi trang (bạn có thể thay đổi số này tùy ý)
 
     return view('vehicleLogs.index', compact('vehicleLogs'));
 }
}
