<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\VehicleInformation;
use Illuminate\Support\Facades\Response;
use App\Models\VehicleLog;
use App\Models\Area;
use App\Models\Floor;
use App\Models\Ticket;
class CameraController extends Controller
{
    public function showCamera()
    {
        $floors = Floor::all();
        return view('camera.show', compact('floors'));
    }
  
    public function getAreas(Request $request)
    {
        $floorId = $request->input('floor_id');

        // Kiểm tra xem floor_id có tồn tại không
        if ($floorId) {
            // Lấy danh sách khu vực dựa trên floor_id
            $areas = Area::where('floor_id', $floorId)->get();
            return response()->json($areas);
        } else {
            // Nếu floor_id không tồn tại, trả về danh sách trống
            return response()->json([]);
        }
    }

    public function getPositions(Request $request)
    {
        $areaId = $request->input('area_id');
    
        // Lấy thông tin vị trí đỗ xe dựa trên area_id
        $area = Area::findOrFail($areaId);
        $positions = [];
    
        // Duyệt qua từng vị trí và lấy thông tin xe tương ứng
        for ($i = 1; $i <= $area->total; $i++) {
            $positionInfo = [];
            $positionInfo['position'] = $i;
    
            // Lấy thông tin xe tại vị trí đỗ xe
            $vehicle = VehicleInformation::where('area_id', $areaId)
                ->where('numberLocation', $i)
                ->first();
    
            if ($vehicle) {
                $positionInfo['license_plate'] = $vehicle->licensePlate;
                $positionInfo['status'] = $vehicle->status;
            } else {
                $positionInfo['license_plate'] = null;
                $positionInfo['status'] = null;
            }
    
            // Thêm thông tin vị trí và xe vào mảng positions
            $positions[] = $positionInfo;
        }
    
        return response()->json(['positions' => $positions]);
    }


    public function captureAndSendImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Gửi ảnh đến FastAPI và xử lý phản hồi
            $response = Http::attach(
                'file',
                file_get_contents($image),
                $image->getClientOriginalName() // Sử dụng tên tệp gốc
            )->post('http://localhost:6123');

            $response = json_decode($response);
            $vehicleAPI = $response->message;

            // Kiểm tra xem có phản hồi và có thông tin biển số xe không
            if (!empty($vehicleAPI)) {
                // Tìm kiếm trong bảng vehicle_information
                $vehicle = VehicleInformation::where('licensePlate', $vehicleAPI)->first();

                // Nếu tìm thấy
                if (!empty($vehicle)) {
                    // Đảo ngược trạng thái
                    $vehicle->status = $vehicle->status === 'active' ? 'inactive' : 'active';
                    $vehicle->save();
                    
                    // Tạo thông báo
                    if ($vehicle->status === 'active') {
                        $message = 'Xe có biển số ' . $vehicleAPI . ' đã vào khu gửi xe.';
                        VehicleLog::create([
                            'status' => 'active',
                            'licensePlate' => $vehicle->licensePlate,
                        ]);
                    } elseif(($vehicle->status === 'inactive')) {
                            VehicleLog::create([
                                'status' => 'inactive',
                                'licensePlate' => $vehicle->licensePlate,
                            ]);
                        $message = 'Xe có biển số ' . $vehicleAPI . ' đã ra khỏi khu gửi xe.';   
                        if(empty($vehicle->fullName)){
                            VehicleInformation::where('licensePlate', $vehicleAPI)->delete();
                        }            
                    }

                    // Trả về thông báo dưới dạng JSON
                    return Response::json(['success' => true, 'message' => $message]);
                }elseif(empty($vehicle)
                        && (preg_match('/^\d{2}-[A-Z]\d{1}\s\d{4,5}$/', $vehicleAPI))){
                            $totalParkingSpots = Area::sum('total');
                            $totalVehicles = VehicleInformation::count();                                             
                            if($totalVehicles < $totalParkingSpots){
                                // Lấy tất cả các khu vực sắp xếp theo priority
                                $areas = Area::orderBy('priority', 'desc')->get();                   
                                $area_id = '';
                                $numberLocation = '';
                                    foreach ($areas as $area) {
                                      $total = $area->total;
                                      $usedLocations = VehicleInformation::where('area_id', $area->id)->pluck('numberLocation')->toArray();
                                      $allLocations = range(1, $total);
                                      $availableLocations = array_diff($allLocations, $usedLocations);
                                       if (!empty($availableLocations)) {
                                          // Lấy vị trí trống nhỏ nhất
                                          $lowestLocation = min($availableLocations);
                                    
                                          $area_id = $area->id;                                             
                                          $numberLocation = $lowestLocation;
                                        
                                           break;
                                       }
                                  }               
                                VehicleInformation::create([
                                    'status' => 'active',
                                    'licensePlate' => $vehicleAPI,                                   
                                    'area_id' => $area_id ?? '',
                                    'numberLocation' => $numberLocation ?? '',
                                    'fullName' => '',
                                    'phone' => '',
                                    'IDCard' => ''
                                ]);
                                Ticket::create([
                                    'licensePlate' => $vehicleAPI,                                   
                                    'area_id' => $area_id ?? '',
                                    'numberLocation' => $numberLocation ?? '',                               
                                ]);

                                VehicleLog::create([
                                    'status' => 'active',
                                    'licensePlate' => $vehicleAPI,
                                ]);
                            
                                $message = 'Xe có biển số ' . $vehicleAPI . ' đã vào khu gửi xe.';
                                return Response::json(['success' => true, 'message' => $message]);
                            }else{
                                $error = 'Khu gửi xe đã hết chỗ.';
                                return Response::json(['error' => 'Khu gửi xe đã hết chỗ.', 'message' => $error]);
                            }
                            
                } else {
                    $error = 'Không nhận dạng được biển số xe.';
                    // Nếu không tìm thấy biển số xe
                    return Response::json(['error' => 'Không nhận dạng được biển số xe.', 'message' => $error]);
                }
            } else {
                // Nếu không có phản hồi hoặc thiếu thông tin biển số xe từ API
                return Response::json(['error' => 'Không có phản hồi hoặc thiếu thông tin biển số xe từ API.'], 400);
            }
        } else {
            // Nếu không tìm thấy tệp ảnh
            return Response::json(['error' => 'No image file found.'], 400);
        }
    }
}
