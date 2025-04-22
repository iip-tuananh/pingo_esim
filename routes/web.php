<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});
Route::get('/crm', function () {
    return view('app');
});
// Route::get('/admin', function () {
//     dd(1);
//     return view('app');
// });
Route::get('/','HomeController@home')->name('home')->middleware(checkLanguage::class);
Route::group(['namespace'=>'Client','middleware' => ['checkLanguage']], function(){
    Route::get('type-product/{id}','PageController@typeproduct');
    Route::get('district/{id}','PageController@district');
    Route::post('ket-qua-tim-kiem','PageController@search')->name('search_result');
    Route::get('dang-nhap.html','AuthController@login')->name('login')->middleware('CheckAuthLogout::class');
    Route::post('dang-nhap.html','AuthController@postLogin')->name('postlogin');
    Route::get('dang-ky.html','AuthController@register')->name('register');
    Route::post('dang-ky.html','AuthController@postRegister')->name('postRegister');
    Route::get('dang-xuat.html','AuthController@logout')->name('logout')->middleware('CheckAuthClient::class');

    Route::get('page/{slug}.html','PageContentController@detail')->name('pagecontent');
    Route::get('dich-vu/danh-muc/{slug}.html','PageController@serviceCateList')->name('serviceCateList');
    Route::get('dich-vu.html','PageController@serviceList')->name('serviceList');
    Route::get('dich-vu/{slug}.html','PageController@serviceDetail')->name('serviceDetail');
    Route::get('about-us.html','PageController@aboutUs')->name('aboutUs');
    Route::get('cong-nghe.html','PageController@technology')->name('technology');
    Route::get('contact-us.html','PageController@contact')->name('contactUs');
    Route::post('contact-us','PageController@postcontact')->name('postcontact');
    Route::get('du-an-tieu-bieu.html','PageController@duanTieuBieu')->name('duanTieuBieu');
    Route::get('du-an-tieu-bieu/{slug}.html','PageController@duanTieuBieuDetail')->name('duanTieuBieuDetail');
    Route::get('cau-hoi-thuong-gap.html','PageController@fag')->name('fag');
    Route::get('giai-phap/{slug}.html','SolutionController@detail')->name('detailSolution');

    Route::group(['prefix'=>'cong-trinh'], function(){
        Route::get('/tat-ca.html','ConstructionController@list')->name('allListConstruction');
        Route::get('{id}.html','ConstructionController@detail')->name('detailConstruction');
    });
    Route::get('quickview/{id}','PageController@quickview')->name('quickview');
    Route::get('bang-gia.html','PageController@baogia')->name('banggia');
    Route::get('loai-xe.html','PageController@carType')->name('carType');

    Route::get('gio-hang.html', 'CartController@listCart')->name('listCart');
    Route::post('add-to-cart', 'CartController@addToCart')->name('add.to.cart');
    Route::post('update-cart', 'CartController@update')->name('update.cart');
    Route::post('remove-from-cart', 'CartController@remove')->name('remove.from.cart');
    Route::get('thanh-toan.html','CartController@checkout')->name('checkout');
    Route::post('thantoan','CartController@postBill')->name('postBill');

    Route::get('dat-ban.html','PageController@orderNow')->name('orderNow');
    Route::get('menu.html','PageController@menu')->name('menu');
    Route::get('account/orders','AuthController@accoungOrder')->name('accoungOrder')->middleware('CheckAuthClient::class');
    Route::get('account/orders/{billid}','AuthController@accoungOrderDetail')->name('accoungOrderDetail')->middleware('CheckAuthClient::class');

    Route::get('auth/google', 'GoogleController@redirectToGoogle')->name('loginGoogle');
    Route::get('auth/google/callback', 'GoogleController@handleGoogleCallback');

    Route::get('auth/facebook', 'FacebookController@redirectToFacebook')->name('loginFacebook');
    Route::get('auth/facebook/callback', 'FacebookController@handleFacebookCallback');
    Route::group(['prefix'=>'blogs'], function(){
        Route::get('/all-blogs.html','BlogController@list')->name('allListBlog');
        Route::get('category/{slug}.html','BlogController@listCateBlog')->name('listCateBlog');
        Route::get('type-category/{slug}.html','BlogController@listTypeBlog')->name('listTypeBlog');
        Route::get('detail/{slug}.html','BlogController@detailBlog')->name('detailBlog');
    });

    Route::get('tat-ca-san-pham.html','ProductController@allProduct')->name('allProduct');
    Route::get('chi-tiet/{sku}.html','ProductController@detail_product')->name('detailProduct');
    Route::get('{danhmuc}.html','ProductController@allListCate')->name('allListProCate');
    Route::get('{danhmuc}/{loaidanhmuc}.html','ProductController@allListType')->name('allListType');
    Route::get('{danhmuc}/{loaidanhmuc}/{thuonghieu}.html','ProductController@allListTypeTwo')->name('allListTypeTwo');
    Route::get('show-product-category','ProductController@getproajax')->name('showProductCategory');

    // Ajax lấy giá
    Route::get('get-price', 'PageController@getPrice')->name('get-price');

    // Câu hỏi thường gặp
    Route::get('faq', 'PageController@faq')->name('faq');

    // How to use Simbye eSIM
    Route::get('how-it-works', 'PageController@howItWorks')->name('howItWorks');

    // Apply voucher
    Route::post('apply-voucher', 'CartController@applyVoucher')->name('applyVoucher');

    // Payos
    Route::get('/payment/success', 'CartController@handleSuccess')->name('orders.payment.success');
});


Route::get('/languages', 'LanguageController@index')->name('languages');
