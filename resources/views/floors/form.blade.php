@extends('layouts.app')
 
@section('title', isset($floors) ? 'Chỉnh sửa tầng' : 'Thêm mới tầng')
 
@section('contents')
  <form action="{{ isset($floors) ? route('floors.update', $floors->id) : route('floors.save') }}" method="post">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="form-group">
              <label for="title">Tên tầng<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ isset($floors) ? $floors->title : '' }}">
              @if ($errors->has('title'))
              <div style="color: red;">
                  @foreach ($errors->get('title') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
          @endif
            </div>      
          </div>
          <div class="card-footer">
            <a type="button" href={{ route('floors') }} class="btn btn-secondary mr-2">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
