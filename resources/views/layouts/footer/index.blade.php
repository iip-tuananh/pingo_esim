<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-columns">
            <div class="footer-column">
                <h3>{{getLanguage('Contactinformation')}}</h3>
                <ul>
                    <li>{{$setting->webname}}</li>
                    <li><i style="margin-right: 10px;" class="fa-solid fa-location-dot"></i>{{$setting->address1}}</li>
                    <li><i style="margin-right: 10px;" class="fa-solid fa-phone"></i><a href="tel:{{str_replace(' ', '', $setting->phone1)}}">{{$setting->phone1}}</a></li>
                    <li><i style="margin-right: 10px;" class="fa-solid fa-envelope"></i><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>{{getLanguage('about_us')}}</h3>
                <ul>
                    <li><a href="{{route('howItWorks')}}">{{getLanguage('how_esim_works')}}</a></li>
                    {{-- <li><a href="{{route('aboutUs')}}">Sim Pingo</a></li> --}}
                    <li><a href="{{route('allListBlog')}}">{{getLanguage('blogs')}}</a></li>
                    <li><a href="{{route('contactUs')}}">{{getLanguage('contact_us')}}</a></li>

                </ul>
            </div>
            <div class="footer-column">
                <h3>{{getLanguage('customer_support')}}</h3>
                <ul>
                    <li><a href="{{route('faq')}}">{{getLanguage('faq')}}</a></li>
                    @foreach ($customerSupport as $item)
                        <li><a href="{{route('pagecontent', $item->slug)}}">{{$item->title}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-column">
                <h3>FOLLOW US</h3>
                <div class="social-icons">
                    <a href="{{$setting->facebook}}" class="social-icon"><i class="fa-brands fa-facebook"></i></a>
                    <a href="{{$setting->twitter}}" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="{{$setting->instagram}}" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="{{$setting->youtube}}" class="social-icon"><i class="fab fa-youtube"></i></a>
                    <a href="{{$setting->linkedin}}" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="top-back">
            <a href="#" class="top-link"><i class="fa-solid fa-arrow-up"></i></a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 {{ $setting->company }}. All rights reserved.</p>
        </div>
    </div>
</footer>
