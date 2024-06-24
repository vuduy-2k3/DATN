@extends('layouts.app')

@section('title', isset($vehicleInformations) ? 'Chỉnh sửa thông tin xe' : 'Thêm mới thông tin xe')

@section('contents')
  <form action="{{ isset($vehicleInformations) ? route('vehicleInformations.update', $vehicleInformations->id) : route('vehicleInformations.save') }}" method="post">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card shadow mb-4">
          <div class="card-body">
            <!-- Các trường thông tin -->
            <div class="form-group">
              <label for="fullName">Tên chủ xe<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fullName" name="fullName" value="{{ old('fullName', isset($vehicleInformations) ? $vehicleInformations->fullName : '') }}">
              @if ($errors->has('fullName'))
              <div style="color: red;">
                  @foreach ($errors->get('fullName') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
              @endif
            </div>
            <div class="form-group">
              <label for="phone">Số điện thoại<span class="text-danger">*</span></label>
              <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', isset($vehicleInformations) ? $vehicleInformations->phone : '') }}">
              @if ($errors->has('phone'))
              <div style="color: red;">
                  @foreach ($errors->get('phone') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
              @endif
            </div> 
            <div class="form-group">
                <label for="IDCard">Số CMND/CCCD<span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="IDCard" name="IDCard" value="{{ old('IDCard', isset($vehicleInformations) ? $vehicleInformations->IDCard : '') }}">
                @if ($errors->has('IDCard'))
                <div style="color: red;">
                    @foreach ($errors->get('IDCard') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
              </div>   
              <div class="form-group">
                <label for="licensePlate">Biển số xe<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="licensePlate" name="licensePlate" value="{{ old('licensePlate', isset($vehicleInformations) ? $vehicleInformations->licensePlate : '') }}">
                @if ($errors->has('licensePlate'))
                <div style="color: red;">
                    @foreach ($errors->get('licensePlate') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
              </div>          
              <div class="form-group">
                <label for="area_id">Khu vực<span class="text-danger">*</span></label>
                <select name="area_id" id="area_id" class="form-control">
                    <option value="">Chọn khu vực</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->id }}" {{ old('area_id', isset($vehicleInformations) && $vehicleInformations->area_id == $area->id ? 'selected' : '') }}>
                          {{ $area->title }}
                      </option>
                  @endforeach  
                </select>
                @if ($errors->has('area_id'))
                <div style="color: red;">
                    @foreach ($errors->get('area_id') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
              </div>   
              <div class="form-group">
                <label for="numberLocation">Vị trí xe trong khu vực<span class="text-danger">*</span></label>
                <select name="numberLocation" id="numberLocation" class="form-control">
                    <option value="">Chọn vị trí xe</option>
                    @if(isset($vehicleInformations))
                        @foreach($numberLocations as $numberLocation)
                            <option value="{{ $numberLocation }}" {{ old('numberLocation', isset($vehicleInformations) && $vehicleInformations->numberLocation == $numberLocation ? 'selected' : '') }}>
                                {{ $numberLocation }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('numberLocation'))
                <div style="color: red;">
                    @foreach ($errors->get('numberLocation') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
                @endif
            </div> 
            <div class="form-group">
              <label for="status">Trạng thái<span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-control">
                <option value="active" {{ old('status', isset($vehicleInformations) ? $vehicleInformations->status : '') == 'active' ? 'selected' : '' }}>Trong khu gửi xe</option>
                <option value="inactive" {{ old('status', isset($vehicleInformations) ? $vehicleInformations->status : '') == 'inactive' ? 'selected' : '' }}>Ngoài khu gửi xe</option>
            </select>
              @if ($errors->has('status'))
              <div style="color: red;">
                  @foreach ($errors->get('status') as $message)
                      <p>{{ $message }}</p>
                  @endforeach
              </div>
          @endif
          </div>  
          </div>
          
          <div class="card-footer">
            <a type="button" href={{ route('vehicleInformations') }} class="btn btn-secondary mr-2">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Script AJAX -->
  <script>
  document.getElementById('area_id').addEventListener('change', function() {
    var area_id = this.value;

    fetch(`/quanlyguixe.com/vehicleInformations/get-number-locations/${area_id}`)
        .then(response => response.json())
        .then(data => {
            var numberLocationSelect = document.getElementById('numberLocation');
            numberLocationSelect.innerHTML = '<option value="">Chọn vị trí xe</option>';

            data.forEach(number => {
                var option = document.createElement('option');
                option.value = number;
                option.text = number;
                numberLocationSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
});
  </script>
@endsection
