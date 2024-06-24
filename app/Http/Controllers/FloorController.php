<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloorController extends Controller
{  
    // Hiển thị danh sách các tầng
    public function index()
    {
        $floors = Floor::orderBy('created_at', 'desc')->paginate(10);
        return view('floors.index', compact('floors'))->with('i', (request()->input('page', 1) - 1) *10);
    }

 // Hiển thị form tạo mới tầng
 public function create()
 {
     return view('floors.form');
 }

 // Lưu tầng mới vào cơ sở dữ liệu
 public function save(Request $request)
 {
     $request->validate([
         'title' => 'required|string|max:255|unique:floors,title',
     ], [
         'title.required' => 'Vui lòng nhập tên tầng',
         'title.max' => 'Tên tầng tối đa 255 ký tự',
         'title.unique' => 'Tên tầng đã tồn tại',
     ]);

     try {
         // Sử dụng transaction để đảm bảo tính nhất quán trong cơ sở dữ liệu
         DB::beginTransaction();

         // Lưu dữ liệu
         Floor::create($request->all());

         // Commit transaction nếu không có lỗi
         DB::commit();

         // Redirect with success message
         return redirect()->route('floors')->with('success', 'Thêm mới tầng thành công.');
     } catch (\Exception $e) {
         // Rollback transaction nếu có lỗi
         DB::rollback();

         // Redirect with error message
         return redirect()->route('floors.create')->with('error', 'Thêm mới tầng thất bại: ' . $e->getMessage());
     }
 }

 // Hiển thị form chỉnh sửa tầng
 public function edit($id)
 {
     $floors = Floor::findOrFail($id);
     return view('floors.form', compact('floors'));
 }

 // Cập nhật tầng trong cơ sở dữ liệu
 public function update(Request $request, $id)
 {
    $request->validate([
        'title' => 'required|string|max:255|regex:/^[a-zA-Z0-9]+$/|unique:floors,title,' . $id,
    ], [
        'title.required' => 'Vui lòng nhập tên tầng',
        'title.max' => 'Tên tầng tối đa 255 ký tự',
        'title.regex' => 'Tên tầng không được nhập ký tự đặc biệt',
        'title.unique' => 'Tên tầng đã tồn tại',
    ]);

     try {
         // Sử dụng transaction
         DB::beginTransaction();

         // Cập nhật dữ liệu
         $floors = Floor::findOrFail($id);
         $floors->update($request->all());

         // Commit transaction nếu không có lỗi
         DB::commit();

         // Redirect with success message
         return redirect()->route('floors')->with('success', 'Cập nhật tầng thành công.');
     } catch (\Exception $e) {
         // Rollback transaction nếu có lỗi
         DB::rollback();

         // Redirect with error message
         return redirect()->route('floors.edit', $id)->with('error', 'Cập nhật tầng thất bại: ' . $e->getMessage());
     }
 }

 // Xóa tầng
 public function delete($id)
 {
     $floor = Floor::findOrFail($id);
 
     // Kiểm tra xem floor này có được sử dụng trong bảng areas hay không
     if ($floor->areas()->exists()) {
         return redirect()->route('floors')->with('error', 'Không thể xóa tầng này vì nó đang được sử dụng trong khu vực.');
     }
 
     $floor->delete();
 
     return redirect()->route('floors')->with('success', 'Xóa tầng thành công.');
 }
 

 // Tìm kiếm
 public function search(Request $request)
 {
     $search = $request->input('search');
     $floors = Floor::orderBy('created_at', 'desc')
         ->where('title', 'like', '%' . $search . '%') // Sử dụng điều kiện like để tìm kiếm không phân biệt chữ hoa chữ thường
         ->paginate(10); // Phân trang với 10 mục trên mỗi trang (bạn có thể thay đổi số này tùy ý)
 
     return view('floors.index', compact('floors'));
 }
}
