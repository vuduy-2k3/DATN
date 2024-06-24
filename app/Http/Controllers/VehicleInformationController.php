<?php

namespace App\Http\Controllers;

use App\Models\VehicleInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Area;
use App\Models\VehicleLog;
use App\Models\Floor;

class VehicleInformationController extends Controller
{
    // Hiển thị danh sách các thông tin xe
    public function index()
    {

        $vehicleInformations = VehicleInformation::orderBy('created_at', 'desc')->paginate(10);
        foreach ($vehicleInformations as $vehicle) {
            $area = Area::find($vehicle->area_id);
            if ($area) {
                $floor = Floor::find($area->floor_id);
                $vehicle->floor_title = $floor ? $floor->title : null;
            } else {
                $vehicle->floor_title = null;
            }
        }
        return view('vehicleInformations.index', compact('vehicleInformations'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

 // Hiển thị form tạo mới thông tin xe
 public function create()
 {
    $areas = \App\Models\Area::all();
     return view('vehicleInformations.form', compact('areas'));
 }

 // Lưu thông tin xe mới vào cơ sở dữ liệu
 public function save(Request $request)
 {
     $request->validate([
         'fullName' => 'required|string|max:255',
         'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
         'IDCard' => ['required', 'regex:/^\d{9}$|^\d{12}$/'],
         'licensePlate' => ['required', 'regex:/^\d{2}-[A-Z]\d{1}\s\d{4,5}$/', 'unique:vehicle_information'],
         'area_id' => 'required|exists:areas,id',
         'numberLocation' => [
            'required',
            Rule::unique('vehicle_information')->where(function ($query) use ($request) {
                return $query->where('area_id', $request->area_id);
            }),
        ],
         'status' => 'required|in:active,inactive',
     ], [
         'fullName.required' => 'Vui lòng nhập tên chủ xe',
         'fullName.max' => 'Tên thông tin xe tối đa 255 ký tự',
         'phone.required' => 'Vui lòng nhập số điện thoại',
         'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0',
         'IDCard.required' => 'Vui lòng nhập số CMND/CCCD',
         'IDCard.regex' => 'Số CMND phải có 9 chữ số hoặc số CCCD phải có 12 chữ số',
         'licensePlate.required' => 'Vui lòng nhập biển số xe',
         'licensePlate.regex' => 'Biển số xe không hợp lệ. Ví dụ: 29-F1 12345 hoặc 29-A1 1234',
         'licensePlate.unique' => 'Biển số xe đã tồn tại',
         'area_id.required' => 'Vui lòng chọn khu vực',
         'area_id.exists' => 'Khu vực không tồn tại',
         'numberLocation.required' => 'Vui lòng chọn vị trí xe',
         'numberLocation.unique' => 'Vị trí này đã được xếp xe',
         'status.required' => 'Vui lòng chọn trạng thái'
     ]);

     try {
         // Sử dụng transaction để đảm bảo tính nhất quán trong cơ sở dữ liệu
         DB::beginTransaction();
        
           
         // Lưu dữ liệu
         VehicleInformation::create($request->all());

         // Commit transaction nếu không có lỗi
         DB::commit();

         // Redirect with success message
         return redirect()->route('vehicleInformations')->with('success', 'Thêm mới thông tin xe thành công.');
     } catch (\Exception $e) {
         // Rollback transaction nếu có lỗi
         DB::rollback();

         // Redirect with error message
         return redirect()->route('vehicleInformations.create')->with('error', 'Thêm mới thông tin xe thất bại: ' . $e->getMessage());
     }
 }

 // Hiển thị form chỉnh sửa thông tin xe
 public function edit($id)
{
    $vehicleInformations = VehicleInformation::findOrFail($id);
    $areas = Area::all();
    
    // Lấy area_id của xe hiện tại
    $selectedAreaId = $vehicleInformations->area_id;
    
    // Truy vấn để lấy đối tượng Area tương ứng với area_id
    $selectedArea = Area::findOrFail($selectedAreaId);

    // Kiểm tra xem selectedArea có tồn tại không trước khi truy cập thuộc tính 'total'
    if ($selectedArea) {
        $numberLocations = range(1, $selectedArea->total);
    } else {
        $numberLocations = [];
    }

    return view('vehicleinformations.form', compact('vehicleInformations', 'areas', 'numberLocations'));
}

 // Cập nhật thông tin xe trong cơ sở dữ liệu
 public function update(Request $request, $id)
 {
    $request->validate([
        'fullName' => 'required|string|max:255',
        'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
        'IDCard' => ['required', 'regex:/^\d{9}$|^\d{12}$/'],
        'licensePlate' => ['required', 'regex:/^\d{2}-[A-Z]\d{1}\s\d{4,5}$/', Rule::unique('vehicle_information')->ignore($id)],
        'area_id' => 'required|exists:areas,id',
        'numberLocation' => [
            'required',
            Rule::unique('vehicle_information')->where(function ($query) use ($request, $id) {
                return $query->where('area_id', $request->area_id)->where('id', '!=', $id);
            }),
        ],
        'status' => 'required|in:active,inactive',
    ], [
        'fullName.required' => 'Vui lòng nhập tên chủ xe',
        'fullName.max' => 'Tên thông tin xe tối đa 255 ký tự',
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0',
        'IDCard.required' => 'Vui lòng nhập số CMND/CCCD',
        'IDCard.regex' => 'Số CMND/CCCD phải có 9 hoặc 12 chữ số',
        'licensePlate.required' => 'Vui lòng nhập biển số xe',
        'licensePlate.regex' => 'Biển số xe không hợp lệ. Ví dụ: 29-F1 12345 hoặc 29-A1 1234',
        'licensePlate.unique' => 'Biển số xe đã tồn tại',
        'area_id.required' => 'Vui lòng chọn khu vực',
        'area_id.exists' => 'Khu vực không tồn tại',
        'numberLocation.required' => 'Vui lòng chọn vị trí xe',
        'numberLocation.unique' => 'Vị trí này đã được xếp xe',
        'status.required' => 'Vui lòng chọn trạng thái'
    ]);


     try {
         // Sử dụng transaction
         DB::beginTransaction();

         
         // Cập nhật dữ liệu
         $vehicleInformations = VehicleInformation::findOrFail($id);
         $oldStatus = $vehicleInformations->status;
           // Kiểm tra và ghi lại lịch sử thay đổi trạng thái
           if ($oldStatus !== $request->status) {
            VehicleLog::create([
                'vehicle_information_id' => $vehicleInformations->id,
                'status' => $request->status,
            ]);
        }
         $vehicleInformations->update($request->all());
         
         // Commit transaction nếu không có lỗi
         DB::commit();

         // Redirect with success message
         return redirect()->route('vehicleInformations')->with('success', 'Cập nhật thông tin xe thành công.');
     } catch (\Exception $e) {
         // Rollback transaction nếu có lỗi
         DB::rollback();

         // Redirect with error message
         return redirect()->route('vehicleInformations.edit', $id)->with('error', 'Cập nhật thông tin xe thất bại: ' . $e->getMessage());
     }
 }

 // Xóa thông tin xe
 public function delete($id)
 {
     $vehicleInformations = VehicleInformation::findOrFail($id);
     $vehicleInformations->delete();

     return redirect()->route('vehicleInformations')->with('success', 'Xóa thông tin xe thành công.');
 }
 

 // Tìm kiếm
 public function search(Request $request)
 {
     $search = $request->input('search');
     $vehicleInformations = VehicleInformation::orderBy('created_at', 'desc')
         ->where('fullName', 'like', '%' . $search . '%')
         ->orWhere('licensePlate', 'like', '%' . $search . '%')
         ->paginate(10); // Phân trang với 10 mục trên mỗi trang (bạn có thể thay đổi số này tùy ý)
 
     return view('vehicleInformations.index', compact('vehicleInformations'));
 }

 public function getNumberLocations($area_id)
 {
     $total = Area::findOrFail($area_id)->total; // Lấy ra trường total từ model Area dựa trên area_id
 
     $totals = [];
     for ($i = 1; $i <= $total; $i++) {
         $totals[] = $i; // Tạo mảng các số từ 1 đến total
     }
 
     return response()->json($totals); // Trả về dữ liệu dưới dạng JSON
 }


}

