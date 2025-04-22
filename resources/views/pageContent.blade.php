@extends('layouts.main.master')
@section('title')
{{$pagecontentdetail->title}}
@endsection
@section('description')
{{$pagecontentdetail->title}}
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<div class="breadcumb-wrapper background-image" data-overlay="title" data-opacity="4" style="background-image: url(&quot;{{url('frontend/img/breadcumb-bg.jpg')}}&quot;);">
   <div class="container z-index-common">
      <h1 class="breadcumb-title">{{$pagecontentdetail->title}}</h1>
      <ul class="breadcumb-menu">
         <li><a href="{{route('home')}}">Trang chủ</a></li>
         <li>{{$pagecontentdetail->title}}</li>
      </ul>
   </div>
</div>
<section class="th-blog-wrapper blog-details space-top space-extra-bottom">
   <div class="container">
      <div class="row gx-60">
         <div class="col-lg-12">
            <div class="th-blog blog-single">
               <div class="blog-content">
                  <h2 class="blog-title">{{$pagecontentdetail->title}}</h2>
                  {!!($pagecontentdetail->content)!!}
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


@endsection