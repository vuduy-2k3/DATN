@extends('layouts.app')
 
@section('title', 'Lịch sử')
 
@section('contents')
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <form action="{{ route('vehicleLogs.search') }}" method="GET" class="mb-3">
          <div class="input-group" style="max-width: 30%;">
              <input type="text" name="search" class="form-control" placeholder="Biển số xe">
              <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
      </form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Biển số xe</th>
              <th>Trạng thái</th>  
            </tr>
          </thead>
          <tbody>
            @php($i = 0)
            @foreach ($vehicleLogs as $row)
              <tr>
                <th>{{ ++$i }}</th>
                <td>{{ $row->licensePlate }}</td>
                <td>
                    @if($row->status == 'active')
                    Vào khu gửi xe
                    @else
                    Ra khỏi khu gửi xe
                    @endif
                  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $vehicleLogs->links() }} <!-- Phân trang -->
    </div>
  </div>
@endsection