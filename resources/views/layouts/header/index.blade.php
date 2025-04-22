<!-- Header -->
<header>
    <div class="container header-container">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ $setting->logo}}" alt="{{ $setting->company }}">
            </a>
        </div>
        <nav class="main-nav d-flex align-items-center hidden-mobile">
            <ul>
                <li><a href="{{ route('howItWorks') }}">{{getLanguage('how_esim_works')}}</a></li>
                <li><a href="#">{{getLanguage('top_up')}}</a></li>
                <li><a href="{{ route('faq') }}">{{getLanguage('faq')}}</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="#" class="btn btn-login">{{getLanguage('login')}}</a>
                <a href="#" class="btn btn-signup">{{getLanguage('signup')}}</a>
            </div>
        </nav>
        <div>
            {{-- {{getLanguage('language')}} --}}
            <select name="lang" id="" style="border-radius: 5px; border: 2px solid var(--primary-color);" onchange="window.location.href='{{ route('languages') }}?code=' + this.value">
                <option value="en-US" {{ Session::get('locale') == 'en-US' ? 'selected' : '' }}>{{getLanguage('english')}}</option>
                <option value="vi" {{ Session::get('locale') == 'vi' ? 'selected' : '' }}>{{getLanguage('vietnamese')}}</option>
            </select>
        </div>
    </div>
</header>
<nav class="main-nav-mobile hidden-desktop">
    <div class="main-nav-mobile-content d-flex align-items-center justify-content-between">
        <div class="main-nav-mobile-logo">
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-house"></i> {{getLanguage('home')}}
            </a>
        </div>
        <div class="main-nav-mobile-menu menu-mobile-open">
            <i class="fa-solid fa-bars"></i> Menu
        </div>
    </div>
</nav>
<div class="menu-mobile-content">
    <div class="menu-mobile-top d-flex align-items-center justify-content-between">
        <div class="login-logout">
            <a href="#" class="login-btn">{{getLanguage('login')}}</a> /
            <a href="#" class="signup-btn">{{getLanguage('signup')}}</a>
        </div>
        <div class="menu-mobile-close">
            <i class="fa-solid fa-times-circle"></i>
        </div>
    </div>
    <ul>
        <li><a href="{{ route('home') }}">{{getLanguage('home')}}</a></li>
        <li><a href="{{ route('howItWorks') }}">{{getLanguage('how_esim_works')}}</a></li>
        <li><a href="#">{{getLanguage('top_up')}}</a></li>
        <li><a href="{{ route('faq') }}">{{getLanguage('faq')}}</a></li>
        <li><a href="{{ route('contactUs') }}">{{getLanguage('contact_us')}}</a></li>
    </ul>
</div>