@extends('layouts.main.master')
@section('title')
    {{ $title_page }}
@endsection
@section('description')
    Tin tức nổi bật và mới nhất
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

        .blog-item {
            text-align: center;
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
            font-size: 2rem;
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
            font-size: 1.5rem;
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
    <script>
        var swiper = new Swiper(".list-blog-slider", {
            slidesPerView: 1.1,
            spaceBetween: 10,
            breakpoints: {
                1200: {
                    slidesPerView: 2,
                    spaceBetween: 50,
                },
                769: {
                    slidesPerView: 1.1,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 1.1,
                    spaceBetween: 10,
                },
                480: {
                    slidesPerView: 1.1,
                    spaceBetween: 10,
                },
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    </script>
@endsection
@section('content')
    <section class="th-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="sec-title text-center">{{ getLanguage('blogs') }}</h2>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="swiper list-blog-slider">
                        <div class="swiper-wrapper">
                            @foreach ($blogs as $category)
                                <div class="swiper-slide blog-item">
                                    <a href="{{ route('listCateBlog', $category->slug) }}" class="blog-item-image">
                                        <img src="{{ url('' . $category->avatar) }}"
                                            alt="{{ languageName($category->name) }}">
                                    </a>
                                    <h3 class="blog-title"><a
                                            href="{{ route('listCateBlog', $category->slug) }}">{{ languageName($category->name) }}</a>
                                    </h3>
                                    <p class="blog-description">{{ $category->description }}</p>
                                    <div class="blog-btn">
                                        <a href="{{ route('listCateBlog', $category->slug) }}"
                                            class="btn btn-primary">{{ getLanguage('learn_more') }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
