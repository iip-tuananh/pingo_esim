@extends('layouts.main.master')
@section('title')
    Loại xe
@endsection
@section('description')
    Loại xe
@endsection
@section('image')
    {{ url('' . $setting->logo) }}
@endsection
@section('css')
@endsection
@section('js')
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-car-type', {
        slidesPerView: 2,
        spaceBetween: 10,
        // pagination: {
        //     el: '.swiper-pagination',
        //     clickable: true,
        // },
        // navigation: {
        //     nextEl: '.swiper-button-next',
        //     prevEl: '.swiper-button-prev',
        // },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 40,
            },
            1280: {
                slidesPerView: 5,
                spaceBetween: 50,
            },
        },
    });
</script>
@endsection
@section('content')
    <div class="breadcumb-wrapper background-image" data-overlay="title" data-opacity="4"
        style="background-image: url(&quot;{{ url('frontend/img/breadcumb-bg.jpg') }}&quot;);">
        <div class="container z-index-common">
            <h1 class="breadcumb-title">Loại xe</h1>
            <ul class="breadcumb-menu">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li>Danh sách loại xe</li>
            </ul>
        </div>
    </div>
    <div class="position-relative space">
        <div class="container">
            @foreach ($carTypes as $item)
            @php
                $images = json_decode($item->images);
            @endphp
                <h2 class="text-2xl font-bold text-blue-800 mb-4 mt-4" style="color: #0066a4">{{ $item->name }}</h2>
                @if (isset($images) && count($images) > 0)
                    <div class="swiper-car-type swiper">
                        <div class="swiper-wrapper">
                            @foreach ($images as $image)
                            <div class="swiper-slide" style="padding: 10px; background-color: #f0f0f0;">
                                <img alt="image" class="mb-2 img-fluid" src="{{ url($image) }}" width="200" loading="lazy"/>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="d-flex mt-4" style="border-bottom: 1px solid #ccc; gap: 50px; align-items: flex-end;">
                    <div style="width: 20%;">
                        <div class="items-center space-x-4">
                            <a class="btn btn-primary" style="background-color: #0066a4; border-color: #0066a4; border-radius: 0;" href="{{ route('banggia') }}?type={{ $item->slug }}">ĐẶT XE NGAY</a>
                        </div>
                    </div>
                    <div>
                        <p class="mt-4">
                            {!! $item->description !!}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
