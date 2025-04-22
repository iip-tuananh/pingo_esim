@extends('layouts.main.master')
@section('title')
    Bảng giá dịch vụ taxi
@endsection
@section('description')
    Bảng giá dịch vụ taxi
@endsection
@section('image')
    {{ url('' . $setting->logo) }}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
    <div class="breadcumb-wrapper background-image" data-overlay="title" data-opacity="4"
        style="background-image: url(&quot;{{ url('frontend/img/breadcumb-bg.jpg') }}&quot;);">
        <div class="container z-index-common">
            <h1 class="breadcumb-title">Bảng giá dịch vụ</h1>
            <ul class="breadcumb-menu">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li>Bảng giá dịch vụ</li>
            </ul>
        </div>
    </div>
    <div class="position-relative space">
        <div class="container">
            <form class="booking-form-area ajax-booking">
                <div class="row">
                    @foreach ($carTypes as $carType)
                    <div class="col-md-6 col-lg-6 col-12">
                        <table
                            style="width: 100%; border-collapse: collapse; background-color: #ffffff; border-style: solid; border-color: #efefef;"
                            border="1" cellpadding="5">
                            <tbody>
                                <tr style="height: 24px;background: #0066a4;">
                                    <td style="width: 100%; height: 24px; text-align: center;    color: white;"
                                        colspan="2"><b>{{$carType->name}}</b></td>
                                </tr>
                                @foreach ($carType->provinces as $province)
                                    <tr style="height: 24px;background: #e2e2e2;">
                                        <td style="width: 100%; height: 24px; text-align: center;    color: black;"
                                            colspan="2">Khu vực: <b>{{$province['province_name']}}</b></td>
                                    </tr>
                                    @foreach ($province['listPrices'] as $price)
                                        @if (!isset($price['parent_id']))
                                            <tr style="height: 24px;">
                                                <td style="width: 46.2511%; height: 24px;">{{$price['place_from']}} =&gt; {{$price['place_to']}}</td>
                                                @if (isset($price->price_min) && isset($price->price_max) && $price->price_max > 0)
                                                    <td style="width: 53.7489%; height: 24px;">từ {{number_format($price->price_min)}}đ đến {{number_format($price->price_max)}}đ</td>
                                                @else
                                                    <td style="width: 53.7489%; height: 24px;">từ {{number_format($price->price_min)}}đ</td>
                                                @endif
                                            </tr>
                                        @else
                                            <tr style="height: 24px;">
                                                <td style="width: 46.2511%; height: 24px;">{{$price['place_from']}}</td>
                                                <td style="width: 53.7489%; height: 24px;">từ {{number_format($price['price_2_way'])}}đ</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
                <p class="form-messages mb-0 mt-3"></p>
            </form>
        </div>
    </div>
@endsection
