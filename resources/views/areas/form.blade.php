@extends('layouts.app')
 
@section('title', isset($areas) ? 'Chỉnh sửa khu vực' : 'Thêm mới khu vực')
 
@section('contents')
  <form action="{{ isset($areas) ? route('areas.update', $areas->id) : route('areas.save') }}" method="post">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="form-group">
              <label for="title">Tên khu vực <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ isset($areas) ? $areas->title : '' }}">
              @if ($errors->has('title'))
              <div style="color: red;">
                  @foreach ($errors->get('title') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
              @endif
            </div>      
            <div class="form-group">
              <label for="total">Số lượng chỗ <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="total" name="total" value="{{ old('total', $areas->total ?? '') }}">
              @if ($errors->has('total'))
              <div style="color: red;">
                  @foreach ($errors->get('total') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
              @endif
            </div>
            <div class="form-group">
        <label for="floor_id">Chọn tầng <span class="text-danger">*</span></label>
        <select class="form-control" id="floor_id" name="floor_id">
            <option value="">Chọn tầng</option>
            @foreach($floors as $floor)
                <option value="{{ $floor->id }}" {{ isset($areas) && $areas->floor_id == $floor->id ? 'selected' : '' }}>
                    {{ $floor->title }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('floor_id'))
            <div style="color: red;">
                @foreach ($errors->get('floor_id') as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
    </div>
            <div class="form-group">
              <label for="priority">Độ ưu tiên <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="priority" name="priority" value="{{ old('priority', $areas->priority ?? '') }}">
              @if ($errors->has('priority'))
              <div style="color: red;">
                  @foreach ($errors->get('priority') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
              @endif
            </div>
          </div>
          <div class="card-footer">
            <a type="button" href={{ route('areas') }} class="btn btn-secondary mr-2">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
