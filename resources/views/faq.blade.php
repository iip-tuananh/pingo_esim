@extends('layouts.main.master')
@section('title')
    Câu hỏi thường gặp
@endsection
@section('description')
    Câu hỏi thường gặp
@endsection
@section('image')
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2>{{ getLanguage('need_help') }}</h2>
                </div>
                <div class="col-md-9">
                    <div class="faq-container">
                        @foreach ($faqs as $key => $faq)
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>{{ languageName($faq->question) }}</h3>
                                    <span class="faq-toggle"><i
                                            class="fa-solid fa-chevron-{{ $key == 0 ? 'up' : 'down' }}"></i></span>
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
