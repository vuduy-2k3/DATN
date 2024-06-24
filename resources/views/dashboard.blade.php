@extends('layouts.app')

@section('title', 'Dashboard')

@section('contents')
<div class="container mt-5">
    <div class="row">
        <!-- Registered Spots -->
        <div class="col-md-3 mb-4 d-flex">
            <div class="card shadow-sm bg-light w-100">
                <div class="card-body d-flex align-items-center rounded" style="background-color: #FFCCCC;">
                    <div>
                        <h5 class="card-title">Số lượng vị trí đã có xe đăng ký</h5>
                        <p class="card-text" id="registered-spots">0</p>
                    </div>
                    <div class="ms-auto">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Unregistered Spots -->
        <div class="col-md-3 mb-4 d-flex">
            <div class="card shadow-sm bg-light w-100">
                <div class="card-body d-flex align-items-center rounded" style="background-color: #CCFFCC;">
                    <div>
                        <h5 class="card-title">Số lượng vị trí chưa có xe đăng ký</h5>
                        <p class="card-text" id="unregistered-spots">0</p>
                    </div>
                    <div class="ms-auto">
                        <i class="fas fa-parking fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cars In -->
        <div class="col-md-3 mb-4 d-flex">
            <div class="card shadow-sm bg-light w-100">
                <div class="card-body d-flex align-items-center rounded" style="background-color: #99CCFF;">
                    <div>
                        <h5 class="card-title">Số lượng xe vào</h5>
                        <p class="card-text" id="cars-in">0</p>
                    </div>
                    <div class="ms-auto">
                        <i class="fas fa-sign-in-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cars Out -->
        <div class="col-md-3 mb-4 d-flex">
            <div class="card shadow-sm bg-light w-100">
                <div class="card-body d-flex align-items-center rounded" style="background-color: #FF9999;">
                    <div>
                        <h5 class="card-title">Số lượng xe ra</h5>
                        <p class="card-text" id="cars-out">0</p>
                    </div>
                    <div class="ms-auto">
                        <i class="fas fa-sign-out-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Pie Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-info text-white">
                    Thống kê theo tình trạng xe
                </div>
                <div class="card-body bg-light">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bar Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-success text-white">
                    Thống kê lượng xe ra vào trong ngày
                </div>
                <div class="card-body bg-light">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gửi yêu cầu AJAX để lấy số lượng vị trí đã đăng ký
        fetch("{{ route('count-registered-spots') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật số lượng vị trí đã đăng ký trong giao diện
                document.getElementById('registered-spots').innerText = data.registered_spots;
            })
            .catch(error => {
                console.error('Error fetching registered spots:', error);
            });

        // Gửi yêu cầu AJAX để lấy số lượng vị trí chưa có xe đăng ký
        fetch("{{ route('count-unregistered-spots') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật số lượng vị trí chưa có xe đăng ký trong giao diện
                document.getElementById('unregistered-spots').innerText = data.unregistered_spots;
            })
            .catch(error => {
                console.error('Error fetching unregistered spots:', error);
            });

        // Gửi yêu cầu AJAX để lấy số lượng xe vào
        fetch("{{ route('count-cars-in') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật số lượng xe vào trong giao diện
                document.getElementById('cars-in').innerText = data.cars_in;
            })
            .catch(error => {
                console.error('Error fetching cars in:', error);
            });

        // Gửi yêu cầu AJAX để lấy số lượng xe ra
        fetch("{{ route('count-cars-out') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật số lượng xe ra trong giao diện
                document.getElementById('cars-out').innerText = data.cars_out;
            })
            .catch(error => {
                console.error('Error fetching cars out:', error);
            });

        // Gửi yêu cầu AJAX để lấy dữ liệu cho biểu đồ tròn
        fetch("{{ route('get-pie-chart-data') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật dữ liệu cho biểu đồ tròn
                var ctxPie = document.getElementById('pieChart').getContext('2d');
                var pieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: ['Xe trong khu', 'Xe ngoài khu'],
                        datasets: [{
                            data: [data.cars_in_lot, data.cars_out_lot],
                            backgroundColor: ['rgba(153, 204, 255, 0.2)', 'rgba(204, 255, 204, 0.2)'],
                            borderColor: ['rgba(153, 204, 255, 1)', 'rgba(204, 255, 204, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching pie chart data:', error);
            });

        // Gửi yêu cầu AJAX để lấy dữ liệu cho biểu đồ thanh
        fetch("{{ route('get-bar-chart-data') }}")
            .then(response => response.json())
            .then(data => {
                // Cập nhật dữ liệu cho biểu đồ thanh
                var ctxBar = document.getElementById('barChart').getContext('2d');
                var barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: ['Xe Vào', 'Xe Ra'],
                        datasets: [{
                            label: 'Lượng Xe',
                            data: [data.cars_in, data.cars_out],
                            backgroundColor: ['rgba(204, 255, 204, 0.2)', 'rgba(255, 204, 204, 0.2)'],
                            borderColor: ['rgba(204, 255, 204, 1)', 'rgba(255, 204, 204, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching bar chart data:', error);
            });
    });
</script>
@endsection
