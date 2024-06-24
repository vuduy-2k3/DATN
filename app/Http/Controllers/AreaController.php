<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use App\Models\Floor;
class AreaController extends Controller
{
    // Hiển thị danh sách các khu vực
    public function index()
    {
        $areas = Area::orderBy('created_at', 'desc')->paginate(10);
        return view('areas.index', compact('areas'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    // Hiển thị form tạo mới khu vực
    public function create()
    {
        $floors = Floor::all();
        return view('areas.form', compact('floors'));
    }

    // Lưu khu vực mới vào cơ sở dữ liệu
    public function save(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:areas,title,NULL,id,floor_id,' . $request->floor_id,
            ],
            'total' => 'required|integer|min:1',
            'floor_id' => 'required|exists:floors,id',
            'priority' => 'required|integer|min:1|unique:areas,priority',
        ], [
            'title.required' => 'Vui lòng nhập tên khu vực',
            'title.max' => 'Tên khu vực tối đa 255 ký tự',
            'title.unique' => 'Tên khu vực đã tồn tại trong tầng này',
            'total.required' => 'Vui lòng nhập số lượng chỗ',
            'total.min' => 'Số lượng chỗ tối thiểu là 1',
            'floor_id.required' => 'Vui lòng chọn tầng',
            'floor_id.exists' => 'Tầng đã chọn không tồn tại',
            'priority.required' => 'Vui lòng nhập độ ưu tiên',
            'priority.min' => 'Độ ưu tiên tối thiểu là 1',
            'priority.unique' => 'Độ ưu tiên đã tồn tại',
        ]);

        try {
            // Sử dụng transaction để đảm bảo tính nhất quán trong cơ sở dữ liệu
            DB::beginTransaction();

            // Lưu dữ liệu
            Area::create($request->all());

            // Commit transaction nếu không có lỗi
            DB::commit();

            // Redirect with success message
            return redirect()->route('areas')->with('success', 'Thêm mới khu vực thành công.');
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi
            DB::rollback();

            // Redirect with error message
            return redirect()->route('areas.create')->with('error', 'Thêm mới khu vực thất bại: ' . $e->getMessage());
        }
    }

    // Hiển thị form chỉnh sửa khu vực
    public function edit($id)
    {
        $areas = Area::findOrFail($id);
        $floors = Floor::all();
        return view('areas.form', compact('areas', 'floors'));
    }

    // Cập nhật khu vực trong cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:areas,title,' . $id . ',id,floor_id,' . $request->floor_id,
            ],
            'total' => 'required|integer|min:1',
            'floor_id' => 'required|exists:floors,id',
            'priority' => 'required|integer|min:1|unique:areas,priority,' . $id,
        ], [
            'title.required' => 'Vui lòng nhập tên khu vực',
            'title.max' => 'Tên khu vực tối đa 255 ký tự',
            'title.unique' => 'Tên khu vực đã tồn tại trong tầng này',
            'total.required' => 'Vui lòng nhập số lượng chỗ',
            'total.min' => 'Số lượng chỗ tối thiểu là 1',
            'floor_id.required' => 'Vui lòng chọn tầng',
            'floor_id.exists' => 'Tầng đã chọn không tồn tại',
            'priority.required' => 'Vui lòng nhập độ ưu tiên',
            'priority.min' => 'Độ ưu tiên tối thiểu là 1',
            'priority.unique' => 'Độ ưu tiên đã tồn tại',
        ]);

        try {
            // Sử dụng transaction
            DB::beginTransaction();
            $areas = Area::findOrFail($id);
        // Kiểm tra xem area_id đã được sử dụng trong bảng vehicle_information hay không
         // Kiểm tra xem area_id đã được sử dụng trong bảng vehicle_information hay không
         $usedInVehicleInformation = DB::table('vehicle_information')
         ->where('area_id', $id)
         ->exists();

         if (!empty($usedInVehicleInformation)
            && !empty($request->total)
            && !empty($request->floor_id)
            && (($request->total != $areas->total)
                 || ($request->floor_id != $areas->floor_id))) {
                return redirect()->back()->with('error', 'Khu vực đang được sử dụng không được sửa tầng và số lượng chỗ.');
}

            // Cập nhật dữ liệu
    
            $areas->update($request->all());

            // Commit transaction nếu không có lỗi
            DB::commit();

            // Redirect with success message
            return redirect()->route('areas')->with('success', 'Cập nhật khu vực thành công.');
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi
            DB::rollback();

            // Redirect with error message
            return redirect()->route('areas.edit', $id)->with('error', 'Cập nhật khu vực thất bại: ' . $e->getMessage());
        }
    }

    // Xóa khu vực
    public function delete($id)
    {
        $areas = Area::findOrFail($id);
        $areas->delete();

        return redirect()->route('areas')->with('success', 'Xóa khu vực thành công.');
    }

    // Tìm kiếm
    public function search(Request $request)
 {
     $search = $request->input('search');
     $areas = Area::orderBy('created_at', 'desc')
         ->where('title', 'like', '%' . $search . '%') // Sử dụng điều kiện like để tìm kiếm không phân biệt chữ hoa chữ thường
         ->paginate(10); // Phân trang với 10 mục trên mỗi trang (bạn có thể thay đổi số này tùy ý)
 
     return view('areas.index', compact('areas'));
 }

    /**
     * Lấy dữ liệu tầng
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}
