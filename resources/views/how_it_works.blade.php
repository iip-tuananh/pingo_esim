@extends('layouts.main.master')
@section('title')
    {{ getLanguage('how_it_works') }}
@endsection
@section('description')
    {{ getLanguage('how_it_works') }}
@endsection
@section('image')
@endsection
@section('css')
    <style>
        .page-how-it-works {
            padding: 50px 0;
        }

        .page-how-it-works h2.title-how-it-works {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 50px;
        }

        .page-how-it-works .steps-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .page-how-it-works .step-card {
            display: grid;
            grid-template-columns: 80px 1fr 1fr;
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }

        .page-how-it-works .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .page-how-it-works .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            background: var(--primary-color);
        }

        .page-how-it-works .step-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .page-how-it-works .step-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .page-how-it-works .step-description {
            font-size: 18px;
            color: #666666;
        }

        .page-how-it-works .step-image {
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .page-how-it-works .step-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .page-how-it-works .step-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
@section('js')
@endsection
@section('content')
    <section class="page-how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title-how-it-works text-center">{{ getLanguage('how_it_works') }}</h2>
                </div>
                <div class="col-md-12">
                    <div class="steps-container">
                        @foreach ($howItWorks as $key => $item)
                        <!-- Step -->
                        <div class="step-card">
                            <div class="step-number">{{$key + 1}}</div>
                            <div class="step-content">
                                <div class="step-title">
                                    {{languageName($item->name)}}
                                </div>
                                <div class="step-description">
                                    {!!languageName($item->content)!!}
                                </div>
                            </div>
                            <div class="step-image">
                                <img src="{{url(''.$item->image)}}" alt="Step {{$key + 1}}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
