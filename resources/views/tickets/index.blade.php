@extends('layouts.app')

@section('title', 'Vé xe')

@section('contents')
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <form action="{{ route('tickets.search') }}" method="GET" class="mb-3">
          <div class="input-group" style="max-width: 30%;">
            <input type="text" name="search" class="form-control" placeholder="Biển số xe">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
          </div>
        </form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tầng</th>
              <th>Khu vực</th>
              <th>Biển số xe</th>
              <th>Hành động</th> <!-- Thêm cột cho nút xuất PDF -->
            </tr>
          </thead>
          <tbody>
            @php($i = 0)
            @foreach ($tickets as $row)
              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $row->floor_title }}</td>
                <td>{{ $row->area->title }}</td>
                <td>{{ $row->licensePlate }}</td>
                <td>
                  @if (!$row->is_exported)
                    <form action="{{ route('tickets.exportPDF', $row->id) }}" method="GET">
                      @csrf
                      <button type="submit" class="btn btn-success">Xuất vé</button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $tickets->links() }} <!-- Phân trang -->
    </div>
  </div>
@endsection
