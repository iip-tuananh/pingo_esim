@extends('layouts.main.master')
@section('title')
    {{ $detail_service->name }}
@endsection
@section('description')
    {{ $detail_service->description }}
@endsection
@section('image')
    {{ url('' . $detail_service->image) }}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
    <div class="breadcumb-wrapper background-image" data-overlay="title" data-opacity="4"
        style="background-image: url(&quot;{{ url('frontend/img/breadcumb-bg.jpg') }}&quot;);">
        <div class="container z-index-common">
            <h1 class="breadcumb-title">{{ $detail_service->name }}</h1>
            <ul class="breadcumb-menu">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li>Dịch vụ</li>
                <li>{{ $detail_service->name }}</li>
            </ul>
        </div>
    </div>
    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="service-single">
                        <h3 class="single-title">{{ $detail_service->name }}</h3>
                        <div class="service-content">
                            {!! languageName($detail_service->content) !!}
                        </div>
                    </div>
                    <div class="row gy-30 justify-content-center">
                        @foreach ($groupCarTypes as $carType)
                        <div class="col-md-6 col-lg-6 col-12">
                            <table
                                style="width: 100%; border-collapse: collapse; background-color: #ffffff; border-style: solid; border-color: #efefef;"
                                border="1" cellpadding="5">
                                <tbody>
                                    <tr style="height: 24px;background: #0066a4;">
                                        <td style="width: 100%; height: 24px; text-align: center;    color: white;"
                                            colspan="2"><b>{{$carType->name}}</b></td>
                                    </tr>
                                    @foreach ($carType->sortedCarServicePrices as $price)
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
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('layouts.main.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection
