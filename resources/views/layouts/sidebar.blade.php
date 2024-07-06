<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href={{ route('dashboard') }}>
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Menu</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Danh mục
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href={{ route('floors') }}>Tầng</a>
                        <a class="nav-link" href={{ route('areas') }}>Khu vực</a>
                    </nav>
                </div>
            
                <a class="nav-link" href={{ route('vehicleInformations') }}>Thông tin xe</a>       
                <a class="nav-link" href={{ route('camera.show') }}>Camera</a>
                <a class="nav-link" href={{ route('tickets') }}>Vé xe</a>
                <a class="nav-link" href={{ route('vehicleLogs') }}>Lịch sử</a>     
            </div>
        </div>   
    </nav>
</div>