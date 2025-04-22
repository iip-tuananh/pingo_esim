@extends('layouts.main.master')
@section('title')
    {{ $title_page }}
@endsection
@section('description')
    {{ $title_page }}
@endsection
@section('image')
    {{ url('' . $banner[0]->image) }}
@endsection
@section('css')
    <style>
        .sec-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 50px;
        }

        .th-blog-wrapper {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .blog-item-image {
            display: block;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        .blog-item-image:hover {
            transform: scale(0.95);
            transition: all 2s ease;
        }

        .blog-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 10px;
            position: relative;
        }

        .blog-title::after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background-color: #000;
            position: absolute;
            bottom: 0;
            left: 0;
            transform: scaleX(0);
            transform-origin: right;
            /* chạy từ phải sang trái */
            transition: transform 0.3s ease;
        }

        .blog-title:hover::after {
            transform: scaleX(1);
        }

        .blog-description {
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 20px;
        }

        .blog-btn .btn {
            margin-top: 20px;
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid #000;
        }

        .blog-btn .btn:hover {
            background-color: #fff;
            color: #000;
        }

        @media (max-width: 768px) {
            .blog-title {
                font-size: 1.5rem;
            }

            .blog-description {
                font-size: 1rem;
            }

            .blog-btn .btn {
                font-size: 1rem;
            }
        }
    </style>
@endsection
@section('js')
@endsection
@section('content')
    <section class="th-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="sec-title text-center">{{ $title_page }}</h2>
                    </div>
                </div>
                <div class="col-lg-12">
                    @foreach ($blogs as $blog)
                        <div class="row blog-list">
                            <div class="col-md-5">
                                <div class="blog-item">
                                    <div class="blog-item-image">
                                        <a href="{{ route('detailBlog', $blog->slug) }}">
                                            <img src="{{ url('' . $blog->image) }}" alt="{{ $blog->title }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="blog-item">
                                    <div class="blog-item-content">
                                        <h3 class="blog-title">
                                            <a href="{{ route('detailBlog', $blog->slug) }}">
                                                {{ languageName($blog->title) }}
                                            </a>
                                        </h3>
                                        <p class="blog-description">{!! languageName($blog->description) !!}</p>
                                        <div class="blog-btn">
                                            <a href="{{ route('detailBlog', $blog->slug) }}"
                                                class="btn btn-primary">{{ getLanguage('learn_more') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="pagination text-center">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
