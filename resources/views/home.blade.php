@extends('layouts.main.master')
@section('title')
    {{ $setting->company }}
@endsection
@section('description')
    {{ $setting->webname }}
@endsection
@section('image')
    {{ url('' . $banner[0]->image) }}
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('frontend/css/home.css')}}">
@endsection
@section('js')
<script>
    var swiper = new Swiper(".home-slider", {
        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
    $(document).ready(function(){
        $('.tab-btn-category').click(function(){
            var id = $(this).data('id');
            $('.tab-btn-category').removeClass('active');
            $(this).addClass('active');
            $.ajax({
                url: "{{ route('showProductCategory') }}",
                type: "GET",
                data: {
                    cate: id
                },
                success: function(response){
                    $('.destinations-grid').html(response.html);
                },
                error: function(xhr, status, error){
                    console.log(xhr.responseText);
                }
            });
        });
        $('.destination-card').click(function(){
            var sku = $(this).data('sku');
            window.location.href = "{{ route('detailProduct',['sku' => ':sku']) }}".replace(':sku', sku);
        });
    });
</script>
@endsection
@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                @foreach($banner as $item)
                <div class="swiper-slide">
                    <img src="{{ url('' . $item->image) }}" alt="{{ $item->title }}" loading="lazy" style="width: 100%; height: auto;">
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Popular Destinations -->
<section class="destinations">
    <div class="container">
        <div class="tabs mb-5">
            @foreach($categories as $key => $item)
            <button class="tab-btn tab-btn-category {{ $key == 0 ? 'active' : '' }}" data-id="{{ $item->id }}">{{ languageName($item->name) }}</button>
            @endforeach
        </div>
        @foreach($categories as $key => $category)
        @if($key == 0)
        <div class="destinations-grid">
            @foreach($category->product as $product)
            @php
                $images = json_decode($product->images);
            @endphp
            <div class="destination-card" data-sku="{{ $product->sku }}">
                <img src="{{ $images[0] }}" alt="{{ languageName($product->name) }}" loading="lazy">
                <span style="padding-left: 10px; font-weight: 600; font-size: 20px;">{{ languageName($product->name) }}</span>
                <hr>
                <div class="destination-card-content d-flex justify-content-between">
                    <p class="mb-0">Starting at <b>{{ formatCurrency($product->min_price_usd, $product->min_price_vnd) }}</b></p>
                    <a href="javascript:void(0)" style="color: #009a61;"><i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
        <a href="#" class="view-all-btn">{{getLanguage('view_all_country')}}</a>
    </div>
</section>

<!-- Advantages Section -->
<section class="advantages">
    <div class="container">
        <div class="advantages-content">
            <div class="advantages-image">
                <img src="{{ url('' . $gioithieu->image) }}" alt="{{ languageName($gioithieu->title) }}" loading="lazy" style="border-radius: 16px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);">
            </div>
            <div class="advantages-text">
                <h2>{{ languageName($gioithieu->title) }}</h2>
                {!! languageName($gioithieu->content) !!}
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2>{{getLanguage('how_it_works')}}</h2>
        <div class="steps-container">
            @foreach($prizes as $key => $prize)
            <div class="step">
                <div class="step-number">{{ $key + 1 }}</div>
                <h3>{{ languageName($prize->name) }}</h3>
                <img src="{{ url('' . $prize->image) }}" alt="{{ languageName($prize->name) }}" class="step-image" loading="lazy">
            </div>
            @endforeach
        </div>
    </div>
</section>

@foreach($aboutUsPageContent as $key => $item)
@if($key % 2 == 0)
<!-- Quick Setup Section -->
<section class="advantages">
    <div class="container">
        <div class="advantages-content">
            <div class="advantages-text">
                <h2>{{ languageName($item->title) }}</h2>
                {!! languageName($item->description) !!}
            </div>
            <div class="advantages-image">
                <img src="{{ url('' . $item->image) }}" alt="{{ languageName($item->title) }}" loading="lazy" style="border-radius: 16px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);">
            </div>
        </div>
    </div>
</section>
@else
<!-- Free Trial Section -->
<section class="advantages">
    <div class="container">
        <div class="advantages-content">
            <div class="advantages-image">
                <img src="{{ url('' . $item->image) }}" alt="{{ languageName($item->title) }}" loading="lazy" style="border-radius: 16px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);">
            </div>
            <div class="advantages-text">
                <h2>{{ languageName($item->title) }}</h2>
                {!! languageName($item->description) !!}
            </div>
        </div>
    </div>
</section>
@endif
@endforeach

<!-- Networks Section -->
<section class="networks">
    <div class="container">
        <h2>Supported Networks</h2>
        <div class="network-logos">
            @foreach($partners as $partner)
            <div class="network-logo">
                <img src="{{ url('' . $partner->image) }}" alt="{{ languageName($partner->name) }}" loading="lazy">
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Section -->
<section class="why-choose">
    <div class="container">
        <h2>{{getLanguage('why_choose_esim')}}</h2>
        <p>{{getLanguage('why_choose_esim_description')}}</p>

        <div class="benefits-grid">
            <div class="benefit-card">
                <img src="{{ url('frontend/images/IPPHONE.svg')}}" alt="Device icon" class="benefit-icon mb-3" width="60" height="60" loading="lazy">
                <h3 style="font-size: 18px; color: #fff; font-weight: 400; text-align: center; margin-bottom: 10px;">{{getLanguage('why_choose_esim_benefit_1')}}</h3>
            </div>
            <div class="benefit-card">
                <img src="{{ url('frontend/images/BLITZZ.svg')}}" alt="Lightning icon" class="benefit-icon mb-3" width="60" height="60" loading="lazy">
                <h3 style="font-size: 18px; color: #fff; font-weight: 400; text-align: center; margin-bottom: 10px;">{{getLanguage('why_choose_esim_benefit_2')}}</h3>
            </div>
            <div class="benefit-card">
                <img src="{{ url('frontend/images/DOLLAA.svg')}}" alt="Dollar icon" class="benefit-icon mb-3" width="60" height="60" loading="lazy">
                <h3 style="font-size: 18px; color: #fff; font-weight: 400; text-align: center; margin-bottom: 10px;">{{getLanguage('why_choose_esim_benefit_3')}}</h3>
            </div>
            <div class="benefit-card">
                <img src="{{ url('frontend/images/Headseet.svg')}}" alt="Support icon" class="benefit-icon mb-3" width="60" height="60" loading="lazy">
                <h3 style="font-size: 18px; color: #fff; font-weight: 400; text-align: center; margin-bottom: 10px;">{{getLanguage('why_choose_esim_benefit_4')}}</h3>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h2>{{getLanguage('need_help')}}</h2>
            </div>
            <div class="col-md-9">
                <div class="faq-container">
                    @foreach($faqs as $key => $faq)
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>{{ languageName($faq->question) }}</h3>
                            <span class="faq-toggle"><i class="fa-solid fa-chevron-{{ $key == 0 ? 'up' : 'down' }}"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>{!! languageName($faq->answer) !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
