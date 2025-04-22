@extends('layouts.main.master')
@section('title')
    {{ languageName($blog_detail->title) }}
@endsection
@section('description')
    {{ languageName($blog_detail->description) }}
@endsection
@section('image')
    {{ url('' . $blog_detail->image) }}
@endsection
@section('css')
    <style>
        .breadcumb-wrapper .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .th-blog-wrapper .container {
            max-width: 1200px;
        }

        .breadcumb-wrapper {
            padding-top: 50px;
        }

        .breadcumb-wrapper .breadcumb-menu {
            display: flex;
            /* justify-content: center; */
            gap: 20px;
            padding-left: 0;
            font-size: 16px;
            color: #999999;
            overflow-x: auto;
            /* scroll ngang */
            white-space: nowrap;
            /* không cho xuống dòng */
            -webkit-overflow-scrolling: touch;
            /* mượt trên iOS */
            scrollbar-width: none;
            /* ẩn scrollbar Firefox */
        }

        .breadcumb-wrapper .breadcumb-menu::-webkit-scrollbar {
            display: none;
            /* ẩn scrollbar Chrome/Safari */
        }

        .breadcumb-wrapper .breadcumb-menu li {
            margin: 0 10px;
            position: relative;
        }

        .breadcumb-wrapper .breadcumb-menu li::after {
            content: '>';
            position: absolute;
            right: -20px;
            top: 2px;
        }

        .breadcumb-wrapper .breadcumb-menu li:last-child::after {
            display: none;
        }

        @media (max-width: 768px) {
            .breadcumb-wrapper .container {
                max-width: 100%;
            }

            .th-blog-wrapper .container {
                max-width: 100%;
            }

            .breadcumb-wrapper .breadcumb-menu {
                overflow-x: auto;
            }
        }

        .title-blog {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #009a61;
        }
    </style>
@endsection
@section('js')
@endsection
@section('content')
    <div class="breadcumb-wrapper background-image">
        <div class="container z-index-common">
            <ul class="breadcumb-menu">
                <li><a href="{{ route('home') }}">{{ getLanguage('home') }}</a></li>
                <li>{{ getLanguage('blogs') }}</li>
                <li>{{ languageName($blog_detail->title) }} </li>
            </ul>
        </div>
    </div>
    <section class="th-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="col-md-12">
                    <h1 class="title-blog">{{ languageName($blog_detail->title) }} </h1>
                </div>
                <div class="col-lg-12">
                    <div class="th-blog blog-single has-post-thumbnail">
                        <div class="blog-content">
                            <h2 class="blog-title">{{ languageName($blog_detail->title) }}</h2>
                            {!! languageName($blog_detail->content) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
