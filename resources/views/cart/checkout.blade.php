<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="flexbox">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=no">
    <title>
        {{ $setting->company }} - Thanh toán đơn hàng
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $setting->company }} - Thanh toán đơn hàng" />
    <link rel="shortcut icon" href="{{ url('' . $setting->favicon) }}" type="image/x-icon">
    <link rel="icon" href="{{ url('' . $setting->favicon) }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"
        integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('frontend/js/notify.min.js') }}" defer></script>
    <link rel="stylesheet" href="{{ url('frontend/css/checkout.min.css') }}">
    <style>
        .list-voucher-modal-popup {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .list-voucher-modal-popup__content {
            background-color: #fff;
            margin: 10% auto;
            padding: 40px 20px 20px;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            position: relative;
        }

        .voucher-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        #list_short_coupon {
            display: -webkit-flex;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            width: 100%;
        }

        #list_short_coupon>span:not(:last-child) {
            margin-right: 5px;
        }

        #list_short_coupon>span {
            overflow: hidden;
            padding: 6px 0;
            position: relative;
            margin-bottom: 5px;
        }

        #list_short_coupon>span span {
            border: 1px solid #338dbc;
            padding: 5px 10px;
            border-radius: 3px;
            background: #fff;
            font-weight: 700;
            color: #338dbc
        }

        #list_short_coupon>span:before {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            border: 1px solid #338dbc;
            background: #fff;
            z-index: 1;
            left: -7px;
            top: 50%;
            position: absolute;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        #list_short_coupon>span:after {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            border: 1px solid #338dbc;
            background: #fff;
            z-index: 1;
            right: -7px;
            top: 50%;
            position: absolute;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .voucher-code {
            border-top: 1px solid #ccc;
            padding: 20px 0;
        }

        .voucher-code__input {
            display: flex;
            align-items: center;
        }

        .voucher-code__input input {
            border: 1px solid #ccc;
            outline: none;
            padding: 10px;
            border-radius: 5px;
            width: 70%;
        }

        .voucher-code__input button {
            border: none;
            outline: none;
            padding: 10px;
            border-radius: 5px;
            background: #338dbc;
            color: #fff;
            cursor: pointer;
        }

        .list-voucher-modal-popup__item {
            overflow: hidden;
            border-radius: 5px;
            margin-bottom: 10px;
            position: relative;
        }

        .list-voucher-modal-popup__item-background {
            border: 1px solid #338dbc;
            padding: 10px 15px;
            border-radius: 5px;
            background: #f5f5f5;
        }

        .list-voucher-modal-popup__item:before {
            content: "";
            display: block;
            width: 16px;
            height: 16px;
            border: 1px solid #338dbc;
            background: #fff;
            z-index: 1;
            left: -7px;
            top: 50%;
            position: absolute;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .list-voucher-modal-popup__item:after {
            content: "";
            display: block;
            width: 16px;
            height: 16px;
            border: 1px solid #338dbc;
            background: #fff;
            z-index: 1;
            right: -7px;
            top: 50%;
            position: absolute;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .list-voucher-modal-popup__item-code {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .list-voucher-modal-popup__item-code span i {
            cursor: pointer;
        }

        .list-voucher-modal-popup__item-description {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .list-voucher-modal-popup__item-button {
            cursor: pointer;
            color: #338dbc;
        }

        .list-voucher-modal-popup__item-more {
            display: none;
        }

        .list-voucher-modal-popup__item-more.active {
            display: block;
        }

        .list-voucher-modal-popup__item-button-collapse {
            display: none;
        }

        .list-voucher-modal-popup__item-button-collapse.active {
            display: block;
        }

        .more-action {
            display: block;
        }

        .more-action.active {
            display: none;
        }

        .collapse-action {
            display: none;
        }

        .collapse-action.active {
            display: block;
        }

    </style>

    <script>
        $(document).ready(function() {
            $('#show_list_voucher').on('click', function() {
                $('#voucher_modal').fadeIn();
            });

            $('#voucher_close').on('click', function() {
                $('#voucher_modal').fadeOut();
            });

            // Đóng khi click ra ngoài content
            $(window).on('click', function(e) {
                if ($(e.target).is('#voucher_modal')) {
                    $('#voucher_modal').fadeOut();
                }
            });

            $('.list-voucher-modal-popup__item-button-more').on('click', function() {
                $(this).closest('.list-voucher-modal-popup__item').find('.list-voucher-modal-popup__item-more').toggleClass('active');
                $(this).closest('.list-voucher-modal-popup__item').find('.more-action').toggleClass('active');
                $(this).closest('.list-voucher-modal-popup__item').find('.collapse-action').toggleClass('active');
            });

            $('.copy-voucher-code').on('click', function() {
                var voucherCode = $(this).data('code');
                navigator.clipboard.writeText(voucherCode);
                $.notify('Đã copy mã giảm giá', 'success');
            });

            $('.apply-voucher').on('click', function() {
                var voucherCode = $(this).data('code') || $('#voucher_code').val();
                $.ajax({
                    url: '{{ route('applyVoucher') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        code: voucherCode,
                    },
                    success: function(response) {
                        if (response.success) {
                            let voucher = response.voucher;
                            $('#voucher_code').val(voucher.code);
                            $('#discount_price').html(response.discount_price_format);
                            $('#total_price').html(response.total);
                            $('#voucher_modal').fadeOut();
                            $.notify(response.message, 'success');
                        } else {
                            $.notify(response.message, 'error');
                        }
                    },
                    error: function(response) {
                        $.notify(response.message, 'error');
                    }
                });
            });
            $('#voucher_code').on('keyup', function() {
                var voucherCode = $(this).val();
                $.ajax({
                    url: '{{ route('applyVoucher') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        code: voucherCode,
                    },
                    success: function(response) {
                        if (response.success) {
                        } else {
                            $('#discount_price').html(response.discount_price_format);
                            $('#total_price').html(response.total);
                        }
                    },
                    error: function(response) {
                        $.notify('Thất bại', 'error');
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="content">
        <form id="checkoutForm" method="post" action="{{ route('postBill') }}">
            @csrf
            <div class="wrap">
                <main class="main">
                    <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">
                        <a href="{{ route('home') }}">{{ $setting->company }}</a> - Thanh toán đơn hàng
                    </h1>
                    <div class="main__content">
                        <article class="animate-floating-labels row">
                            <div class="col col--two">
                                <section class="section">
                                    <div class="section__header">
                                        <div class="layout-flex">
                                            <h2 class="section__title layout-flex__item layout-flex__item--stretch">
                                                <i
                                                    class="fa fa-id-card-o fa-lg section__title--icon hide-on-desktop"></i>
                                                Thông tin nhận hàng
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="section__content">
                                        <div class="fieldset">
                                            <div class="field "
                                                data-bind-class="{'field--show-floating-label': billing.name}">
                                                <div class="field__input-wrapper">
                                                    <input name="billingName" id="billingName" type="text"
                                                        class="field__input" placeholder="Họ và tên"
                                                        value="{{ $profile ? $profile->name : '' }}" required>
                                                </div>
                                            </div><br>
                                            <div class="field " data-bind-class="{'field--show-floating-label': email}">
                                                <div class="field__input-wrapper">
                                                    <input name="billingEmail" id="email" type="Email"
                                                        class="field__input" placeholder="Email"
                                                        value="{{ $profile ? $profile->email : '' }}">
                                                </div>
                                            </div><br>

                                            <div class="field "
                                                data-bind-class="{'field--show-floating-label': billing.phone}">
                                                <div class="field__input-wrapper">
                                                    <input name="billingPhone" id="billingPhone" type="tel"
                                                        class="field__input" pattern="\d+"
                                                        placeholder="Số điện thoại (tùy chọn)"
                                                        value="{{ $profile ? $profile->phone : '' }}" required>
                                                </div>
                                            </div><br>
                                            <div class="field "
                                                data-bind-class="{'field--show-floating-label': billing.address}">
                                                <div class="field__input-wrapper">
                                                    <input name="billingAddress" id="billingAddress" type="tel"
                                                        class="field__input" placeholder="Số zalo nhận thông báo"
                                                        pattern="\d+"
                                                        value="" required>
                                                </div>
                                            </div><br><br>
                                        </div>
                                    </div>
                                </section>
                                <div class="fieldset">
                                    <h3 class="visually-hidden">Ghi chú</h3>
                                    <div class="field " data-bind-class="{'field--show-floating-label': note}">
                                        <div class="field__input-wrapper">
                                            <textarea name="note" id="note" type="text" class="field__input" placeholder=" Ghi chú (tùy chọn)"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col--two">
                                <section class="section">
                                    <div class="section__header">
                                        <div class="layout-flex">
                                            <h2 class="section__title layout-flex__item layout-flex__item--stretch">
                                                <i class="fa fa-truck fa-lg section__title--icon hide-on-desktop"></i>
                                                Phương thức thanh toán
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="section__content" id="shippingMethodList">
                                        <div class="content-box">
                                            <div class="content-box__row">
                                                <p>Bước 1: Chọn phương thức thanh toán</p>
                                                <p>Bước 2: Nhập thông tin thanh toán</p>
                                                <p>Bước 3: Xác nhận đơn hàng</p>
                                                <p>Bước 4: Thông báo kích esim qua email và zalo</p>
                                                <p>Quý khách cần hỗ trợ xin liên hệ:</p>
                                                <ul>
                                                    <li>Điện thoại: <a
                                                            href="tel:{{ $setting->phone1 }}">{{ $setting->phone1 }}</a>
                                                    </li>
                                                    <li>Email: <a
                                                            href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </article>
                        <div class="field__input-btn-wrapper field__input-btn-wrapper--vertical hide-on-desktop">
                            <button type="submit" class="btn btn-checkout spinner">
                                <span class="spinner-label">ĐẶT HÀNG</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="spinner-loader">
                                    <use href="#spinner"></use>
                                </svg>
                            </button>
                            <a href="{{ route('home') }}" class="previous-link">
                                <i class="previous-link__arrow">❮</i>
                                <span class="previous-link__content">Tiếp tục mua sắm</span>
                            </a>
                        </div>

                        <style>
                            .separator {
                                display: inline-block;
                                position: relative;
                                padding: 0 1rem;
                                color: #6c757d;
                                font-weight: 500;
                                font-size: 1.2rem;
                            }

                            .separator::before,
                            .separator::after {
                                content: "";
                                position: absolute;
                                top: 50%;
                                width: 30px;
                                height: 1px;
                                background: #dee2e6;
                            }

                            .separator::before {
                                left: 0;
                                transform: translateX(-100%);
                            }

                            .separator::after {
                                right: 0;
                                transform: translateX(100%);
                            }
                        </style>

                        <div style="text-align: center">
                            <span class="mx-3 separator">Hoặc thanh toán qua</span>
                        </div>


                        <div id="paypal-button-container" style="margin-top: 1rem;"></div>


                    </div>
                </main>
                <aside class="sidebar">
                    @php
                        $total_vnd = 0;
                        $total_usd = 0;
                        $qty = 0;
                    @endphp
                    @foreach ((array) session('cart') as $id => $details)
                        @php
                            $total_vnd += $details['price_vnd'] * $details['quantity'];
                            $total_usd += $details['price_usd'] * $details['quantity'];
                            $qty += $details['quantity'];
                        @endphp
                    @endforeach
                    <input type="text" id="total_vnd" name="total_vnd" value="{{ $total_vnd }}" hidden>
                    <input type="text" id="total_usd" name="total_usd" value="{{ $total_usd }}" hidden>
                    <input type="text" id="qty" name="qty" value="{{ $qty }}" hidden>
                    <div class="sidebar__header">
                        <h2 class="sidebar__title">
                            Đơn hàng ({{ $qty }} sản phẩm)
                        </h2>
                    </div>
                    <div class="sidebar__content">
                        <div id="order-summary" class="order-summary order-summary--is-collapsed">
                            <div class="order-summary__sections">
                                <div
                                    class="order-summary__section order-summary__section--product-list order-summary__section--is-scrollable order-summary--collapse-element">
                                    <table class="product-table">
                                        <caption class="visually-hidden">Chi tiết đơn hàng</caption>
                                        <thead class="product-table__header">
                                            <tr>
                                                <th>
                                                    <span class="visually-hidden">Ảnh sản phẩm</span>
                                                </th>
                                                <th>
                                                    <span class="visually-hidden">Mô tả</span>
                                                </th>
                                                <th>
                                                    <span class="visually-hidden">Sổ lượng</span>
                                                </th>
                                                <th>
                                                    <span class="visually-hidden">Đơn giá</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('cart') as $id => $details)
                                                <tr class="product">
                                                    <td class="product__image">
                                                        <div class="product-thumbnail">
                                                            <div class="product-thumbnail__wrapper" data-tg-static>
                                                                <img src="{{ url('' . $details['image']) }}"
                                                                    alt="" class="product-thumbnail__image">
                                                            </div>
                                                            <span
                                                                class="product-thumbnail__quantity">{{ $details['quantity'] }}</span>
                                                        </div>
                                                    </td>
                                                    <th class="product__description">
                                                        <span class="product__description__name">
                                                            {{ languageName($details['product_name']) }}
                                                        </span>
                                                        <span class="product__description__name text-muted">
                                                            {{ $details['name'] }}
                                                        </span>
                                                    </th>
                                                    <td class="product__quantity visually-hidden"><em>Số lượng:</em>
                                                        {{ $details['quantity'] }}</td>
                                                    <td class="product__price">
                                                        {{ formatCurrency($details['price_usd'], $details['price_vnd']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="voucher-code">
                                    <div class="voucher-code__input" style="display: flex; align-items: center;">
                                        <input type="text" name="voucher_code" id="voucher_code"
                                            placeholder="Nhập mã giảm giá">
                                        <button type="button" id="apply_voucher" class="apply-voucher">Áp dụng</button>
                                    </div>
                                    <div class="show-list-voucher" id="show_list_voucher" style="cursor: pointer; margin-top: 10px;">
                                        <svg width="15" height="10" viewBox="0 0 18 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.3337 5.3335V2.00016C17.3337 1.07516 16.5837 0.333496 15.667 0.333496H2.33366C1.41699 0.333496 0.675326 1.07516 0.675326 2.00016V5.3335C1.59199 5.3335 2.33366 6.0835 2.33366 7.00016C2.33366 7.91683 1.59199 8.66683 0.666992 8.66683V12.0002C0.666992 12.9168 1.41699 13.6668 2.33366 13.6668H15.667C16.5837 13.6668 17.3337 12.9168 17.3337 12.0002V8.66683C16.417 8.66683 15.667 7.91683 15.667 7.00016C15.667 6.0835 16.417 5.3335 17.3337 5.3335ZM15.667 4.11683C14.6753 4.69183 14.0003 5.77516 14.0003 7.00016C14.0003 8.22516 14.6753 9.3085 15.667 9.8835V12.0002H2.33366V9.8835C3.32533 9.3085 4.00033 8.22516 4.00033 7.00016C4.00033 5.76683 3.33366 4.69183 2.34199 4.11683L2.33366 2.00016H15.667V4.11683ZM9.83366 9.50016H8.16699V11.1668H9.83366V9.50016ZM8.16699 6.16683H9.83366V7.8335H8.16699V6.16683ZM9.83366 2.8335H8.16699V4.50016H9.83366V2.8335Z"
                                                fill="#318DBB"></path>
                                        </svg>
                                        Xem thêm mã giảm giá
                                    </div>
                                    <div id="list_short_coupon">
                                        @foreach ($vouchers as $voucher)
                                            <span>
                                                <span data-code="{{ $voucher->code }}">{{ $voucher->name }}</span>
                                            </span>
                                        @endforeach
                                    </div>

                                    <div class="list-voucher-modal-popup" id="voucher_modal" style="display: none;">
                                        <div class="list-voucher-modal-popup__content">
                                            <span class="voucher-close" id="voucher_close">&times;</span>
                                            @foreach ($all_vouchers as $item)
                                                <div class="list-voucher-modal-popup__item">
                                                    <div class="list-voucher-modal-popup__item-background">
                                                        <div class="list-voucher-modal-popup__item-code">
                                                            <span
                                                                data-code="{{ $item->code }}"><b>{{ $item->name }}</b></span>
                                                            <span>Mã giảm giá: {{ $item->code }} <span class="list-voucher-modal-popup__item-button copy-voucher-code" data-code="{{ $item->code }}"><i
                                                                        class="fa fa-copy"></i></span></span>
                                                        </div>
                                                        <div class="list-voucher-modal-popup__item-description">
                                                            <span>{{ $item->description }}</span>
                                                            <span class="list-voucher-modal-popup__item-button apply-voucher" data-code="{{ $item->code }}">Sử dụng</span>
                                                        </div>
                                                        <div class="list-voucher-modal-popup__item-button list-voucher-modal-popup__item-button-more">
                                                            <span class="more-action">Xem chi tiết <i class="fa fa-arrow-down"></i></span>
                                                            <span class="collapse-action">Thu gọn <i class="fa fa-arrow-up"></i></span>
                                                        </div>
                                                        <div class="list-voucher-modal-popup__item-more">
                                                            <p><b>Thông tin chi tiết</b></p>
                                                            {!! $item->content !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="order-summary__section order-summary__section--total-lines order-summary--collapse-element"
                                    data-define="{subTotalPriceText: '{{ formatCurrency($total_usd, $total_vnd) }}'}"
                                    data-tg-refresh="refreshOrderTotalPrice" id="orderSummary">
                                    <table class="total-line-table">
                                        <caption class="visually-hidden">Tổng giá trị</caption>
                                        <thead>
                                            <tr>
                                                <td><span class="visually-hidden">Mô tả</span></td>
                                                <td><span class="visually-hidden">Giá tiền</span></td>
                                            </tr>
                                        </thead>
                                        <tbody class="total-line-table__tbody">
                                            <tr class="total-line total-line--subtotal">
                                                <th class="total-line__name">
                                                    Tạm tính
                                                </th>
                                                <td class="total-line__price">
                                                    {{ formatCurrency($total_usd, $total_vnd) }}</td>
                                            </tr>
                                            <tr class="total-line total-line--shipping-fee">
                                                <th class="total-line__name">
                                                    Giảm giá
                                                </th>
                                                <td class="total-line__price" id="discount_price">
                                                    </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="total-line-table__footer">
                                            <tr class="total-line payment-due">
                                                <th class="total-line__name">
                                                    <span class="payment-due__label-total">
                                                        Tổng cộng
                                                    </span>
                                                </th>
                                                <td class="total-line__price">
                                                    <span
                                                        class="payment-due__price" id="total_price">{{ formatCurrency($total_usd, $total_vnd) }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div
                                    class="order-summary__nav field__input-btn-wrapper hide-on-mobile layout-flex--row-reverse">
                                    <button type="submit" class="btn btn-checkout spinner">
                                        <span class="spinner-label">Tiếp tục thanh toán</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="spinner-loader">
                                            <use href="#spinner"></use>
                                        </svg>
                                    </button>
                                    <a href="{{ route('home') }}" class="previous-link">
                                        <i class="fa fa-arrow-left"></i>
                                        <span class="previous-link__content">Tiếp tục mua sắm</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</body>

</html>

<script>
    window.PAYPAL_CLIENT_ID = "{{ config('paypal')[config('paypal.mode')]['client_id'] }}";
</script>
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal')[config('paypal.mode')]['client_id'] }}&currency=USD"></script>


<script>
    window.cartItems = {!! json_encode(session('cart', [])) !!};
    console.log(window.cartItems)
    window.cartItemsObj = {!! json_encode(session('cart', [])) !!};
    // Chuyển thành array
    window.cartItems = Object.values(window.cartItemsObj);
    // Tính tổng USD ngay trong Blade
    window.cartTotalUSD = window.cartItems
        .reduce((sum, item) => sum + parseFloat(item.price_usd) * parseInt(item.quantity), 0);


</script>

<script>
    (function(){
        const items = window.cartItems.map(item => ({
            name: item.name,
            sku: item.idOption.toString(),
            description: `proId: ${item.idPro}`,
            unit_amount: {
                currency_code: 'USD',
                value: parseFloat(item.price_usd).toFixed(2)
            },
            quantity: item.quantity.toString()
        }));

        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            paypal.Buttons({
                style: { layout: 'vertical', color: 'blue', shape: 'pill' },

                // 1. Gọi API /paypal/create-order để server tạo order và trả về orderID
                createOrder: (data, actions) => {
                    return fetch('/api/paypal/create-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            items: items,
                        })
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('Không thể kết nối server');
                            return res.json();
                        })
                        .then(json => {
                            if (!json.orderID) {
                                console.error('Response thiếu orderID:', json);
                                throw new Error('Không lấy được orderID');
                            }
                            return json.orderID;   // trả về đúng key từ server
                        });
                },

                // 2. Khi user approve, capture trên PayPal rồi gọi API /paypal/capture-order
                onApprove: (data, actions) => {
                    return actions.order.capture().then(details => {
                        return fetch('/api/paypal/capture-order', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                details: details
                            })
                        })
                            .then(res => {
                                if (!res.ok) {
                                    // lỗi HTTP 4xx/5xx
                                    throw new Error('Lưu giao dịch thất bại, status: ' + res.status);
                                }
                                return res.json();
                            }).then(json => {
                                alert('Thanh toán thành công')
                                console.log('Thanh toán thành công:', json);
                                // window.location.href = '/dat-hang-thanh-cong';
                            })
                            .catch(err => {
                                console.error(err);
                                alert(err.message || 'Xảy ra lỗi không xác định');
                            });
                    });
                },

                onError: err => {
                    console.error('PayPal Error:', err);
                    alert('Thanh toán thất bại, vui lòng thử lại.');
                }

            }).render('#paypal-button-container');
        });

    })();
</script>
