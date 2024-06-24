@extends('layouts.app')
 
@section('title', 'Thông tin xe')
 
@section('contents')
  <div class="card shadow mb-4">
    <div class="card-body">
      <a href="{{ route('vehicleInformations.create') }}" class="btn btn-primary mb-3">Thêm mới</a>
      <div class="table-responsive">
        <form action="{{ route('vehicleInformations.search') }}" method="GET" class="mb-3">
          <div class="input-group" style="max-width: 30%;">
              <input type="text" name="search" class="form-control" placeholder="Tên chủ xe, biển số xe">
              <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
      </form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên chủ xe</th>   
              <th>Số điện thoại</th> 
              <th>Số CMND/CCCD</th> 
              <th>Biển số xe</th> 
              <th>Tầng</th>
              <th>Khu vực</th>
              <th>Vị trí xe trong khu vực</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @php($i = 0)
            @foreach ($vehicleInformations as $row)
              <tr>
                <th>{{ ++$i }}</th>
                <td>{{ $row->fullName }}</td>
                <td>{{ $row->phone }}</td>
                <td>{{ $row->IDCard }}</td>
                <td>{{ $row->licensePlate }}</td>
                <td>{{ $row->floor_title }}</td> 
                <td>{{ $row->area->title }}</td>
                <td>{{ $row->numberLocation }}</td>
                <td>
                  @if($row->status == 'active')
                  Trong khu gửi xe
                  @else
                  Ngoài khu gửi xe
                  @endif
                </td>
                <td>
                  @if($row->fullName !== '')
                  <a href="{{ route('vehicleInformations.edit', $row->id) }}" class="btn btn-warning">Chỉnh sửa</a>
                  @endif
                  <a href="{{ route('vehicleInformations.delete', $row->id) }}" class="btn btn-danger">Xóa</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $vehicleInformations->links() }} <!-- Phân trang -->
    </div>
  </div>
@endsection
