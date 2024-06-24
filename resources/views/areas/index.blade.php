@extends('layouts.app')

@section('title', 'Khu vực')

@section('contents')
  <div class="card shadow mb-4">
    <div class="card-body">
      <a href="{{ route('areas.create') }}" class="btn btn-primary mb-3">Thêm mới</a>
      <div class="table-responsive">
        <form action="{{ route('areas.search') }}" method="GET" class="mb-3">
          <div class="input-group" style="max-width: 30%;">
            <input type="text" name="search" class="form-control" placeholder="Tên khu vực">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
        </form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên khu vực</th>
              <th>Số lượng chỗ</th>
              <th>Tầng</th>
              <th>Độ ưu tiên</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @php($i = 0)
            @foreach ($areas as $row)
              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $row->title }}</td>
                <td>{{ $row->total }}</td>
                <td>{{ $row->floor->title }}</td>
                <td>{{ $row->priority }}</td>
                <td>
                  <a href="{{ route('areas.edit', $row->id) }}" class="btn btn-warning">Chỉnh sửa</a>
                  <a href="{{ route('areas.delete', $row->id) }}" class="btn btn-danger">Xóa</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $areas->links() }} <!-- Phân trang -->
    </div>
  </div>
@endsection
