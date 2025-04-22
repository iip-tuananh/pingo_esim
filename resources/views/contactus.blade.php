@extends('layouts.main.master')
@section('title')
    Liên hệ với chúng tôi
@endsection
@section('description')
    Liên hệ với chúng tôi
@endsection
@section('image')
    {{ url('' . $setting->logo) }}
@endsection
@section('css')
<style>
    .contact-form-wrapper form#contact .form-group{
        position: relative;
        margin-bottom: 20px;
    }
    .contact-form-wrapper form#contact .form-group svg{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 20px;
    }
    .contact-form-wrapper form#contact .form-group input {
        height: 46px;
    }
    .contact-form-wrapper form#contact .form-btn button{
        height: 46px;
        padding: 0 20px;
        background-color: var(--primary-color);
        color: #fff;
        border-radius: 5px;
        border: 1px solid var(--primary-color);
    }
    .contact-form-wrapper form#contact .form-btn button:hover{
        background-color: var(--primary-color-hover);
        color: var(--primary-color);
    }
</style>
@endsection
@section('js')
@endsection
@section('content')
    <section class="space-bottom position-relative">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="sec-title" style="text-align: center; padding-bottom: 50px;
                    padding-top: 50px; font-size: 3rem; font-weight: 800;">{{ getLanguage('contact_us') }}</h2>
                </div>
            </div>
            <div class="contact-form-wrapper" style="margin-bottom: 50px;">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="contact-box_info">
                            {{-- <p class="contact-box_text">Địa chỉ</p> --}}
                            <h5 class="contact-box_link"><i style="margin-right: 10px;" class="fa-solid fa-location-dot"></i> {{ $setting->address1 }}</h5>
                        </div>
                        <div class="contact-box_info">
                            {{-- <p class="contact-box_text">Số điện thoại</p> --}}
                            <h5 class="contact-box_link"><a href="tel:{{ $setting->phone1 }}"><i style="margin-right: 10px;" class="fa-solid fa-phone"></i> {{ $setting->phone1 }}</a></h5>
                        </div>
                        <div class="contact-box_info">
                            {{-- <p class="contact-box_text">Email</p> --}}
                            <h5 class="contact-box_link"><a href="mailto:{{ $setting->email }}"><i style="margin-right: 10px;" class="fa-solid fa-envelope"></i> {{ $setting->email }}</a></h5>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form action="{{ route('postcontact') }}" id="contact" method="post"
                            class="contact-form ajax-contact">
                            @csrf
                            <div class="title-area mb-30 text-center text-lg-start">
                                <h2 class="sec-title">{{ getLanguage('Sendinformation') }}</h2>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Name" required> <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Email"> <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="number" class="form-control" name="phone" id="phone"
                                        placeholder="Phone Number" required> <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="form-group col-12">
                                    <textarea name="mess" id="message" cols="30" rows="3" class="form-control" placeholder="Message"></textarea>
                                </div>
                                <div class="form-btn col-12 text-center"><button class="th-btn fw-btn"><i
                                            class="fa-solid fa-paper-plane"></i> {{ getLanguage('sendmessage') }}</button></div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
