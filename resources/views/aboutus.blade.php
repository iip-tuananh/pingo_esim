@extends('layouts.main.master')
@section('title')
Giới thiệu về CÔNG TY TNHH PHÁT TRIỂN CÔNG NGHỆ NĂNG LƯỢNG TÁI TẠO
@endsection
@section('description')
Giới thiệu về CÔNG TY TNHH PHÁT TRIỂN CÔNG NGHỆ NĂNG LƯỢNG TÁI TẠO
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="aboutus_section">
   <div class="container">
       <div class="row">
           <div class="col-lg-7 col-md-12 col-sm-12 col-12">
               <div class="about_images_wrapper position-relative">
                   <figure class="mb-0 about_main_image">
                       <img src="{{url('frontend/images/about_aboutus_main_image.jpg')}}" alt="" class="img-fluid">
                   </figure>
                   <figure class="mb-0 about_left_image position-absolute">
                       <img src="{{url('frontend/images/about_aboutus_image2.jpg')}}" alt="" class="img-fluid">
                   </figure>
                   <div class="about_left_box_wrapper">
                       <figure class="mb-0">
                           <img src="{{url('frontend/images/about_left_box_image.png')}}" alt="" class="img-fluid hover-effect">
                       </figure>
                       <div class="left_box_content">
                           <div class="span_wrapper">
                               <span class="counter">25</span>
                               <span class="plus">+</span>
                           </div>
                           <p class="text-size-18 mb-0">Years of Experience</p>
                       </div>
                   </div>
                   <figure class="mb-0 about_aboutus_shape position-absolute left_right_shape">
                       <img src="{{$setting->logo}}" alt="" class="img-fluid">
                   </figure>
               </div>
           </div>
           <div class="col-lg-5 col-md-12 col-sm-12 col-12">
               <div class="about_content aos-init aos-animate" data-aos="fade-right">
                   <h2>Chào mừng bạn đến với Shibainu VN.</h2>
                   <p class="text-size-18 about_first_p">Hãy đồng hành cùng chúng tôi để có những khoảnh khắc tuyệt vời nhất cùng những bạn Shiba inu đáng yêu này.</p>
                   <p class="text-size-18">Trại chó shiba Inu BaNguyen là một trại chó được xây dựng chuyên biệt cho mục đích nhân giống và chăm sóc thú cưng, nằm ở ngoại ô Hà Nội.

                     Với định hướng rõ ràng, chúng tôi tập trung chuyên sâu nghiên cứu và nhân giống chó Shiba Inu và Akita Inu, với mục đích tìm đến vẻ đẹp hoàn thiện nhất của hai giống chó này, đồng thời hướng tới là đơn vị dẫn đầu phát triển chuyên nghiệp 2 giống chó này ở Việt Nam.</p>
                   
               </div>
           </div>
       </div>
   </div>
</section>
<section class="team_section">
   <div class="container">
       <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12 col-12">
               <div class="team_content">
                   <h2>Về chúng tôi</h2>
               </div>
           </div>
       </div>
       <div class="row aos-init aos-animate" data-aos="fade-up">
           <div class="col-lg-12 col-md-12 col-sm-12 col-12 product_content">
               {!!$gioithieu->content!!}
           </div>
       </div>
   </div>
</section>
<section class="testimonials_section position-relative">
   <div class="container">
      <div class="row">
         <div class="col-lg-6 col-md-6 col-sm-12 col-12" data-aos="fade-right">
            <div class="testimonials_heading_content">
               <h6>Feedback</h6>
               <h2>Khách hàng nói gì?</h2>
            </div>
            <div class="owl-carousel owl-theme">
               @foreach ($reviewcus as $item)
               <div class="item">
                  <div class="testimonials_content position-relative">
                     <p class="paragraph">{!!languageName($item->content)!!}</p>
                     <ul class="list-unstyled">
                        <li><i class="fa-solid fa-star"></i></li>
                        <li><i class="fa-solid fa-star"></i></li>
                        <li><i class="fa-solid fa-star"></i></li>
                        <li><i class="fa-solid fa-star"></i></li>
                        <li><i class="fa-solid fa-star"></i></li>
                     </ul>
                     <h3>{{languageName($item->name)}}</h3>
                     <p class="text-size-18">{{languageName($item->position)}}</p>
                     <figure class="testimonials_quote mb-0 position-absolute">
                        <img src="{{url('frontend/images/testimonials_quote.png')}}" alt="testimonials_quote" class="img-fluid">
                     </figure>
                  </div>
               </div>
               @endforeach
               
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-sm-12 col-12 d-md-block d-none">
            <div class="testimonials_image">
               <figure class="testimonials_image1 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image1.png')}}" alt="testimonials_image1" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_image2 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image2.jpg')}}" alt="testimonials_image2" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_image3 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image3.jpg')}}" alt="testimonials_image3" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_image4 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image4.jpg')}}" alt="testimonials_image4" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_image5 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image5.jpg')}}" alt="testimonials_image5" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_image6 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_image6.jpg')}}" alt="testimonials_image6" class="img-fluid hover-effect">
               </figure>
               <figure class="testimonials_circle1 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_circle1.png')}}" alt="testimonials_circle1" class="img-fluid">
               </figure>
               <figure class="testimonials_circle2 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_circle2.png')}}" alt="testimonials_circle2" class="img-fluid">
               </figure>
               <figure class="testimonials_circle3 mb-0 position-absolute">
                  <img src="{{url('frontend/images/testimonials_circle3.png')}}" alt="testimonials_circle3" class="img-fluid">
               </figure>
               <figure class="testimonials_rightside_shape mb-0 position-absolute left_right_shape">
                  <img src="{{url('frontend/images/testimonials_rightside_shape.png')}}" alt="testimonials_rightside_shape" class="img-fluid">
               </figure>
            </div>
         </div>
      </div>
      <figure class="testimonials_left_shape mb-0 position-absolute top_bottom_shape">
         <img src="{{url('frontend/images/testimonials_left_shape.png')}}" alt="testimonials_left_shape" class="img-fluid">
      </figure>
      <figure class="testimonials_right_shape mb-0 position-absolute top_bottom_shape">
         <img src="{{url('frontend/images/testimonials_right_shape.png')}}" alt="testimonials_right_shape" class="img-fluid">
      </figure>
   </div>
</section>
<section class="services_section position-relative">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="services_content">
               <h6>OUR SERVICES</h6>
               <h2>Dịch vụ của chúng tôi</h2>
            </div>
         </div>
      </div>
      <div class="row position-relative" data-aos="fade-up">
         <div class="owl-carousel owl-theme">
            @foreach ($servicehome as $key => $item)
            <div class="item">
               <div class="services_box box{{$key+1}}">
                  <figure>
                     <img src="{{$item->image}}" alt="" class="img-fluid hover-effect">
                  </figure>
                  <h3>{{$item->name}}</h3>
                  <p class="text-size-18 line_3">{{languageName($item->description)}}</p>
                  <div class="btn_wrapper">
                     <a class="text-decoration-none" href="{{route('serviceDetail',['slug'=>$item->slug])}}">Chi tiết<i class="fa-solid fa-arrow-right"></i></a>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
         <figure class="mb-0 services_box_shape position-absolute left_right_shape">
            <img src="{{url('frontend/images/services_box_left_shape.png')}}" alt="" class="img-fluid">
         </figure>
      </div>
      <figure class="mb-0 services_left_shape position-absolute top_bottom_shape">
         <img src="{{url('frontend/images/services_dog_shape.png')}}" alt="" class="img-fluid">
      </figure>
      <figure class="mb-0 services_right_shape position-absolute top_bottom_shape">
         <img src="{{url('frontend/images/services_cat_shape.png')}}" alt="" class="img-fluid">
      </figure>
   </div>
</section>
@endsection