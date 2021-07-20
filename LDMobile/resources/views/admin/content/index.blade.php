@extends("admin.layout")
@section("sidebar-dashboard") sidebar-link-selected @stop
@section("content-title") Bảng điều khiển @stop
@section("content")
<div class="card table-card">
    <div class="card-header">
        <h5>Thống kê tháng {{$currentMonth}}</h5>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card bg-c-red total-card">
            <div class="card-block">
                <div class="text-left">
                    <h4>{{$totalBillInMonth}}</h4>
                    <p class="m-0">Tổng số đơn hàng</p>
                     <i class="far fa-shopping-cart mat-icon"></i>
                </div>
            </div> 
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-c-green total-card">
            <div class="card-block">
                <div class="text-left">
                    <h4>{{ number_format($totalMoneyInMonth, 0, '', '.') }}<sup>đ</sup></h4>
                    <p class="m-0">Doanh thu</p>
                    <i class="fal fa-money-bill-alt mat-icon"></i>
                </div>
               
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-c-blue total-card">
            <div class="card-block">
                <div class="text-left">
                    <h4>{{$totalAccountInMonth}}</h4>
                    <p class="m-0">Lượt đăng ký thành viên</p>
                    <i class="fal fa-user mat-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@stop