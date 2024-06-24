@extends('layouts.app')
 
@section('title', 'Tầng')
 
@section('contents')
  <div class="card shadow mb-4">
    <div class="card-body">
      <a href="{{ route('floors.create') }}" class="btn btn-primary mb-3">Thêm mới</a>
      <div class="table-responsive">
        <form action="{{ route('floors.search') }}" method="GET" class="mb-3">
          <div class="input-group" style="max-width: 30%;">
              <input type="text" name="search" class="form-control" placeholder="Tên tầng">
              <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
      </form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên tầng</th>        
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @php($i = 0)
            @foreach ($floors as $row)
              <tr>
                <th>{{ ++$i }}</th>
                <td>{{ $row->title }}</td>
                <td>
                  <a href="{{ route('floors.edit', $row->id) }}" class="btn btn-warning">Chỉnh sửa</a>
                  <a href="{{ route('floors.delete', $row->id) }}" class="btn btn-danger">Xóa</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $floors->links() }} <!-- Phân trang -->
    </div>
  </div>
@endsection