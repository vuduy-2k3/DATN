@extends('layouts.app')

@section('title', 'Camera')

@section('contents')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <!-- Nửa bên trái -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- Phần Bộ Lọc -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h1 class="h3 text-center">Vị trí xe</h1>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="floor">Tầng</label>
                                        <select class="form-control" id="floor">
                                            <option value="">Chọn tầng</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="area">Khu Vực:</label>
                                        <select class="form-control" id="area" disabled>
                                            <option value="">Chọn khu vực</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="position">Vị trí đỗ xe:</label>
                                        <div id="position-container">
                                            <!-- Các vị trí đỗ xe sẽ được hiển thị ở đây -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nửa bên phải -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h1 class="h3 text-center">Camera</h1>
                        </div>
                        <div class="card-body text-center">
                            <div id="camera-container" class="text-center">
                                <video id="video" autoplay playsinline class="w-100" style="max-width: 60%; border: 1px solid black;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                            </div>
                            <div class="mt-3">
                                <button id="captureButton" class="btn btn-primary">Chụp Ảnh</button>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted">Nhấn nút Space hoặc nút "Chụp Ảnh" để chụp ảnh</p>
                            </div>
                            <div id="captured-image-container" class="mt-3" style="display: none;">
                                <img id="captured-image" src="" alt="Captured Image" class="mt-3" style="max-width: 100%; border: 1px solid black;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Load danh sách khu vực khi thay đổi tầng
    $('#floor').change(function() {
        var floorId = $(this).val();
        if (floorId) {
            $.ajax({
                url: '{{ route('camera.getAreas') }}',
                type: 'GET',
                data: { floor_id: floorId },
                success: function(data) {
                    $('#area').empty().append('<option value="">Chọn Khu Vực</option>');
                    $.each(data, function(index, area) {
                        $('#area').append('<option value="' + area.id + '">' + area.title + '</option>');
                    });
                    $('#area').prop('disabled', false);
                    
                    // Reset danh sách vị trí khi chọn tầng mới
                    $('#position-container').empty();
                }
            });
        } else {
            $('#area').empty().append('<option value="">Chọn Khu Vực</option>').prop('disabled', true);
            $('#position-container').empty();
        }
    });

    // Load danh sách vị trí đỗ xe khi thay đổi khu vực
    $('#area').change(function() {
        var areaId = $(this).val();
        if (areaId) {
            loadParkingPositions(areaId);
        } else {
            $('#position-container').empty();
        }
    });

    // Hàm load danh sách vị trí đỗ xe
    function loadParkingPositions(areaId) {
        if (areaId) {
            $.ajax({
                url: '{{ route('camera.getPositions') }}',
                type: 'GET',
                data: { area_id: areaId },
                success: function(data) {
                    var positions = data.positions;
                    var positionsHtml = '';
                    positions.forEach(function(position) {
                        var licensePlate = position.license_plate ? position.license_plate : 'Không có';
                        var statusText = position.status ? (position.status === 'active' ? 'Trong khu gửi xe' : 'Ngoài khu gửi xe') : 'Không có';
                        positionsHtml += '<div>Vị trí ' + position.position + ': ' + '&nbsp;&nbsp;&nbsp;';
                        positionsHtml += 'Biển số xe: ' + licensePlate + '&nbsp;&nbsp;&nbsp;'; // Thêm khoảng cách
                        positionsHtml += 'Trạng thái: ' + statusText + '</div>';
                    });
                    $('#position-container').html(positionsHtml);
                }
            });
        } else {
            $('#position-container').empty();
        }
    }
});


    document.addEventListener('DOMContentLoaded', function() {
        // Access the camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.error('Error accessing camera: ' + err.message);
            });

        // Capture image when Space is pressed
        document.addEventListener('keydown', function(event) {
            if (event.code === 'Space') {
                event.preventDefault();
                captureImage();
            }
        });

        // Capture image when Capture button is clicked
        document.getElementById('captureButton').addEventListener('click', function() {
            captureImage();
        });

        function captureImage() {
            var video = document.getElementById('video');
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            var dataURL = canvas.toDataURL('image/png');
            sendImageToAI(dataURL);
        }

        function sendImageToAI(dataURL) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Chuyển đổi dữ liệu ảnh từ base64 sang Blob object
            var byteString = atob(dataURL.split(',')[1]);
            var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
            var arrayBuffer = new ArrayBuffer(byteString.length);
            var uint8Array = new Uint8Array(arrayBuffer);
            for (var i = 0; i < byteString.length; i++) {
                uint8Array[i] = byteString.charCodeAt(i);
            }
            var blob = new Blob([arrayBuffer], { type: mimeString });

            // Tạo formData object và thêm dữ liệu ảnh vào
            var formData = new FormData();
            formData.append('image', blob, 'image.png');

            // Gửi yêu cầu AJAX với dữ liệu tệp
            $.ajax({
                url: '{{ route('capture-and-send-image') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false, // Không cần thiết khi gửi FormData
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log('AI Response:', response);
                    // Hiển thị thông báo toastr
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.error);
                    }

                    // Hiển thị ảnh đã chụp xuống dưới
                    var capturedImage = document.getElementById('captured-image');
                    capturedImage.src = URL.createObjectURL(blob);
                    document.getElementById('captured-image-container').style.display = 'block';

                    // Thiết lập setTimeout để ẩn ảnh sau 5 giây
                    setTimeout(function() {
                        document.getElementById('captured-image-container').style.display = 'none';
                    }, 5000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error sending image to AI:', textStatus, errorThrown);
                }
            });
        }
    });
</script>
@endsection
