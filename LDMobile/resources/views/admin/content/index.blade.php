@extends("admin.layout")
@section("sidebar-dashboard") sidebar-link-selected @stop
@section("content-title") Bảng điều khiển @stop
@section("content")

<div class="pt-10 pb-10 mb-10 box-shadow-border-radius">
    <div class="statistics-title fz-20">Thống kê tháng {{$currentMonth}}</div>
</div>

{{-- thống kê nhanh --}}
<div class="row mb-50">
    <div class="col-lg-4">
        <div class="quick-stats-card red-bg">
            <div>
                <div class="fz-26 fw-600">{{$totalBillInMonth}}</div>
                <div>Tổng số đơn hàng</div>
            </div>
            <i class="fas fa-shopping-cart fz-40"></i>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="quick-stats-card success-bg">
            <div>
                <div class="fz-26 fw-600">{{ number_format($totalMoneyInMonth, 0, '', '.') }}<sup>đ</sup></div>
                <div>Doanh thu</div>
            </div>
            <i class="fas fa-hand-holding-usd fz-40"></i>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="quick-stats-card info-bg">
            <div>
                <div class="fz-26 fw-600">{{$totalAccountInMonth}}</div>
                <div>Lượt đăng ký thành viên</div>
            </div>
            <i class="fas fa-user fz-40"></i>
        </div>
    </div>
</div>

{{-- best sellers & số lượt đánh giá & truy cập --}}
<div class="row mb-50">
    {{-- BXH 5 sp bán chạy --}}
    <div class="col-lg-8 col-sm-12">
        <div class="box-shadow-border-radius">
            {{-- title --}}
            <div class="pt-20 pb-20">
                <div class="statistics-title fz-18">Best sellers</div>
            </div>
            <hr class="m-0">
            {{-- list best sellers --}}
            <table class="table">
                <tbody>
                    @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="p-0">
                            <div class="best-sellers">
                                <div class="d-flex align-items-center">
                                    @if ($i == 1)
                                        <div class="rank-number red">{{$i}}</div>
                                    @elseif($i == 2)
                                        <div class="rank-number yellow">{{$i}}</div>
                                    @elseif($i == 3)
                                        <div class="rank-number success-color">{{$i}}</div>
                                    @else
                                        <div class="rank-number gray-1">
                                            {{$i}}
                                        </div>
                                    @endif
                                    <div class="d-flex ml-40">
                                        <img src="images/phone/iphone_12_red.jpg" alt="best seller product" width="70px">
                                        <div class="ml-10">
                                            <div class="d-flex align-items-center fw-600">
                                                iPhone 12
                                                <i class="fas fa-circle ml-5 mr-5 fz-5"></i>
                                                Đỏ
                                            </div>
                                            <div class="fz-14">Ram: 4 GB</div>
                                            <div class="fz-14">Dung lượng: 64 GB</div>
                                        </div>
                                    </div>
                                </div>
                                <div>Đã bán: 10 chiếc<i class="fas fa-trophy-alt ml-10 yellow"></i></div>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    {{-- số lượt đánh giá & số lượt truy cập web & app --}}
    <div class="col-lg-4 col-sm-12">
        {{-- số lượt đánh giá --}}
        <div class="box-shadow-border-radius d-flex justify-content-between mb-30">
            <div class="p-20">
                <div class="fz-22 fw-600 black">100</div>
                <div class="text-color">Lượt đánh giá trong tháng</div>
            </div>
            <div class="icon-right-stats purple-bg">
                <i class="fas fa-comments fz-40 white"></i>
            </div>
        </div>
        {{-- số lượt truy cập web --}}
        <div class="box-shadow-border-radius d-flex justify-content-between mb-30">
            <div class="p-20">
                <div class="fz-22 fw-600 black">100</div>
                <div class="text-color">Lượt truy cập trên Web trong tháng</div>
            </div>
            <div class="icon-right-stats blue-bg">
                <i class="fas fa-globe fz-40 white"></i>
            </div>
        </div>
        {{-- số lượt truy cập app --}}
        <div class="box-shadow-border-radius d-flex justify-content-between mb-30">
            <div class="p-20">
                <div class="fz-22 fw-600 black">100</div>
                <div class="text-color">Lượt truy cập trên App trong tháng</div>
            </div>
            <div class="icon-right-stats success-bg">
                <i class="fab fa-android fz-40 white"></i>
            </div>
            
        </div>
    </div>
</div>

{{-- trạng thái đơn hàng --}}
<div class="row mb-50">
    <div class="col-lg-12">
        <div class="box-shadow-border-radius">
            <div class="row">
                <input type="hidden" id="total-order" value="{{$lst_orderStatus['total']}}">
                <div class="col-lg-3">
                    <div class="p-20">
                        <div>Đơn hàng tiếp nhận</div>
                        <div class="fz-26 fw-600 mb-10">{{$lst_orderStatus['received']}}</div>
                        <div id="received-order" data-qty="{{$lst_orderStatus['received']}}" class="order-progress-bar">
                            <div class="received-progress-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-20">
                        <div>Đơn hàng xác nhận</div>
                        <div class="fz-26 fw-600 mb-10">{{$lst_orderStatus['confirmed']}}</div>
                        <div id="confirmed-order" data-qty="{{$lst_orderStatus['confirmed']}}" class="order-progress-bar">
                            <div class="confirmed-progress-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-20">
                        <div>Đơn hàng thành công</div>
                        <div class="fz-26 fw-600 mb-10">{{$lst_orderStatus['success']}}</div>
                        <div id="successfull-order" data-qty="{{$lst_orderStatus['success']}}" class="order-progress-bar">
                            <div class="success-progress-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-20">
                        <div>Đơn hàng đã hủy</div>
                        <div class="fz-26 fw-600 mb-10">{{$lst_orderStatus['cancelled']}}</div>
                        <div id="cancelled-order" data-qty="{{$lst_orderStatus['cancelled']}}" class="order-progress-bar">
                            <div class="cancelled-progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop