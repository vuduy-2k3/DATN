<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Area;
use App\Models\Floor;
use Barryvdh\DomPDF\PDF as PDF;
class TicketController extends Controller
{
    protected $pdf;

      // Hiển thị danh sách các lịch sử
      public function index()
      {
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);
        foreach ($tickets as $ticket) {
            $area = Area::find($ticket->area_id);
            if ($area) {
                $floor = Floor::find($area->floor_id);
                $ticket->floor_title = $floor ? $floor->title : null;
            } else {
                $ticket->floor_title = null;
            }
        }
          return view('tickets.index', compact('tickets'))->with('i', (request()->input('page', 1) - 1) *10);
      }
 
       // Tìm kiếm
  public function search(Request $request)
  {
      $search = $request->input('search');
      $tickets = Ticket::orderBy('created_at', 'desc')
          ->where('licensePlate', 'like', '%' . $search . '%') // Sử dụng điều kiện like để tìm kiếm không phân biệt chữ hoa chữ thường
          ->paginate(10); // Phân trang với 10 mục trên mỗi trang (bạn có thể thay đổi số này tùy ý)
  
      return view('tickets.index', compact('tickets'));
  }

  public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    public function exportPDF($ticketId)
    {
        // Lấy ticket từ id
        $ticket = Ticket::findOrFail($ticketId);
    
        // Kiểm tra xem ticket đã được xuất PDF chưa
        if ($ticket->is_exported) {
            // Nếu đã xuất rồi thì có thể xử lý redirect hoặc thông báo lỗi
            return redirect()->back()->with('error', 'Lượt xe vào này đã được xuất vé.');
        }
    
        // Cập nhật trường is_exported cho ticket
        $ticket->update(['is_exported' => true]);
    
        // Load thông tin khu vực và tầng (floor)
        $area = Area::find($ticket->area_id);
        if ($area) {
            $floor = Floor::find($area->floor_id);
            $ticket->floor_title = $floor ? $floor->title : null;
        } else {
            $ticket->floor_title = null;
        }
    
        // Load view và tạo PDF
        $pdf = $this->pdf->loadView('tickets.ticket_pdf', compact('ticket'));
    
        // Tạo tên file PDF dựa trên id của ticket
        $fileName = 'ticket_' . $ticket->id . '.pdf';
    
        // Download PDF và trả về response
        return $pdf->download($fileName);
    }
    

}
