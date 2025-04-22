<aside class="sidebar-area">
    <div class="widget widget_categories">
        <div class="card shadow" style="max-width: 600px; border: none;">
            <nav class="bookback">
                <div class="nav nav-tabs" id="nav-tabFormSubmitDisanbay" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                        aria-selected="true"><i class="fas fa-plane"></i> Sân bay</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-profile" type="button" role="tab"
                        aria-controls="nav-profile" aria-selected="false"><i class="fas fa-road"></i> Đường dài</button>
                </div>
            </nav>
            <div class="tab-content p-3" id="navFormSubmitDisanbay-tabContent">
                <div class="tab-pane fade active show" id="nav-home" role="tabpanel"
                    aria-labelledby="nav-home-tab">
                    <form id="submitFormDisanbay" class="booking-form4 wow fadeInUp background-image "
                        style="visibility: visible; animation-name: fadeInUp;">
                        <div class="row">
                            <div class="form-group col-sm-12 d-flex align-items-center input-group">
                                <label for="locationInputFrom" style="width: 30%;"> Điểm đi</label>
                                <input type="text" class="form-control" name="diemdi" id="locationInputFrom"
                                    placeholder="Điểm đi">
                            </div>
                            <div class="form-group col-sm-12 d-flex align-items-center input-group">
                                <label for="locationInputTo" style="width: 30%;"> &nbsp;&nbsp;&nbsp;Điểm đến</label>
                                <input type="text" class="form-control" value="Nội Bài" name="diemden"
                                    id="locationInputTo" placeholder="Điểm đến">
                            </div>
                            <button type="button" class="btn btn-primary" id="rotate_btn" rel="tooltip" data-placement="top" title="Đảo chiều" data-bs-toggle="tooltip">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </button>
                            <hr>
                            <div class="form-group col-md-4">
                                <input type="checkbox" name="twoway" id="twoWay" value="1">
                                <label for="twoWay">Hai chiều</label>
                            </div>
                            <div class="form-group col-md-8 input-group w-66">
                                <label for="loaixe" class="w-50">Loại xe</label>
                                <select name="loaixe" id="loaixe" class="form-select w-50">
                                    <option value="" selected="selected">Loại xe</option>
                                    @foreach ($carTypes as $item)
                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="date-pick form-control" name="ngaydi"
                                    id="date-pick" placeholder="Ngày đi">
                                <i class="fa-light fa-calendar-days"></i>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="time-pick form-control" name="giodi"
                                    id="time-pick" placeholder="Giờ đi">
                                <i class="fa-light fa-clock"></i>
                            </div>
                            <div class="form-btn col-12 text-center mt-3"><button type="submit"
                                    class="th-btn radius-btn bookdisanbay w-50">Kiểm tra giá<i
                                        class="fa-regular fa-arrow-right ms-2"></i></button></div>
                        </div>
                        <p class="form-messages mb-0 mt-3"></p>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                    aria-labelledby="nav-profile-tab">
                    <form id="submitFormDiduongdai" class="booking-form4 wow fadeInUp background-image "
                        style="visibility: visible; animation-name: fadeInUp;">
                        <div class="row">
                            <div class="form-group col-sm-6 input-group">
                                <label for="locationInputFrom" style="width: 30%;"> Điểm đi</label>
                                <input type="text" class="form-control" name="diemdididuongdai"
                                    id="locationInputFrom" placeholder="Điểm đi">
                            </div>
                            <div class="form-group col-sm-6 input-group">
                                <label for="locationInputTo" style="width: 30%;"> &nbsp;&nbsp;&nbsp;Điểm đến</label>
                                <input type="text" class="form-control" name="diemdendiduongdai"
                                    id="locationInputTo" placeholder="Điểm đến">
                            </div>
                            <button type="button" class="btn btn-primary" id="rotate_btn_duongdai" rel="tooltip" data-placement="top" title="Đảo ngược" data-bs-toggle="tooltip">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </button>
                            <hr>
                            <div class="form-group col-md-4">
                                <input type="checkbox" name="twowayduongdai" id="twoWayDuongdai" value="1">
                                <label for="twoWayDuongdai">Hai chiều</label>
                            </div>
                            <div class="form-group col-md-8 input-group w-66">
                                <label for="loaixediduongdai" class="w-50">Loại xe</label>
                                <select name="loaixediduongdai" id="loaixediduongdai" class="form-select w-50">
                                    <option value="" selected="selected">Loại xe</option>
                                    @foreach ($carTypes as $item)
                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="date-pick form-control"
                                    name="ngaydididuongdai" id="date-pick" placeholder="Ngày đi">
                                <i class="fa-light fa-calendar-days"></i>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="time-pick form-control"
                                    name="giodididuongdai" id="time-pick" placeholder="Giờ đi">
                                <i class="fa-light fa-clock"></i>
                            </div>
                            <div class="form-btn col-12 text-center mt-3"><button type="submit"
                                    class="th-btn radius-btn bookdisanbay w-50">Kiểm tra giá<i
                                        class="fa-regular fa-arrow-right ms-2"></i></button></div>
                        </div>
                        <p class="form-messages mb-0 mt-3"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="widget widget_offer background-image"
        style="background-image: url(&quot;{{ url('frontend/img/offer.jpg') }}&quot;);">
        <div class="offer-banner">
            <h5 class="banner-title">Cần giúp đỡ liên hệ ngay với chúng tôi</h5>
            <div class="banner-logo"><img src="{{ $setting->logo }}" alt="Taxiar"></div>
            <div class="offer">
                <h6 class="offer-title">Hotline</h6>
                <a class="offter-num" href="tel:{{ $setting->phone1 }}">{{ $setting->phone1 }}</a>
            </div>
            <a href="{{ route('lienHe') }}" class="th-btn style3">Liên hệ<i
                    class="fa-regular fa-arrow-right"></i></a>
        </div>
    </div>
</aside>
<!-- Bootstrap Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #0066a4;">
        <h5 class="modal-title text-white text-center" style="width: 100%;" id="bookingModalLabel"><i class="fa-solid fa-info-circle"></i> XÁC NHẬN THÔNG TIN</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body text-center">
        <p>Giá cước đã bao gồm phí ra, vào sân bay Nội Bài, chưa bao gồm phí cầu đường khác</p>
        <h3 id="popup_price" class="text-warning">190,000₫</h3>
        <p>Nhận báo giá chi tiết xin vui lòng liên hệ</p>
        <a class="btn btn-danger" href="tel:{{ str_replace(' ', '', $setting->phone1) }}"><i class="fa-thin fa-phone"></i> {{ $setting->phone1 }}</a>
        <hr>
        <p class="text-muted">Vui lòng đặt xe 45 phút trước giờ đón để đảm bảo giá này!</p>
        <p>Để lại số điện thoại của Quý khách, chúng tôi sẽ liên lạc lại ngay!</p>
        <form>
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" placeholder="Điện thoại" required style="border-radius: 10px; height: 46px; background-color: #efefef; ::placeholder { opacity: 0.5; }">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" placeholder="Họ và tên" required style="border-radius: 10px; height: 46px; background-color: #efefef; ::placeholder { opacity: 0.5; }">
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-warning">XÁC NHẬN ĐẶT CHUYẾN</button>
        </div>
    </div>
    </div>
</div>
<script>
    $('#submitFormDiduongdai').validate({
        rules: {
            // "namediduongdai": {
            //     required: true,
            // },
            // "phonediduongdai": {
            //     required: true,
            //     minlength: 8
            // },
            "loaixediduongdai": {
                required: true,
            },
            "diemdididuongdai": {
                required: true,
            },
            "diemdendiduongdai": {
                required: true,
            },
            "ngaydididuongdai": {
                required: true,
            }
        },
        messages: {
            // "namediduongdai": {
            //     required: "Tên bạn là gì?",
            // },
            // "phonediduongdai": {
            //     required: "Nhập sdt liên hệ",
            // },
            "loaixediduongdai": {
                required: "Chọn loại xe"
            },
            "diemdididuongdai": {
                required: "Tôi đón bạn ở đâu?"
            },
            "diemdendiduongdai": {
                required: "Chọn điểm đến của bạn"
            },
            "ngaydididuongdai": {
                required: "Bạn muốn đi hôm nào?"
            }
        },
        errorPlacement: function(error, element) {
            // Tạo hoặc tìm thẻ <span> để hiển thị lỗi
            var errorSpan = $(element).next("span.error-message");
            if (errorSpan.length === 0) {
                errorSpan = $("<span class='error-message w-100'></span>").insertAfter(element);
            }
            errorSpan.text(error.text());
        },
        success: function(label, element) {
            $(element).next("span.error-message").remove(); // Xóa thông báo lỗi khi nhập đúng
        },
        submitHandler: function(form) {
            $.ajax({
                url: "{{ route('get-price') }}",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#submitFormDiduongdai").serializeArray(),
                success: function(response) {
                    $('#popup_price').html(response.price);
                },
                error: function() {
                    jQuery.notify("Gửi thông tin thất bại", "error");
                }
            });
            $('#bookingModal').modal('show');
            // $.ajax({
            //     url: "https://script.google.com/macros/s/AKfycbzltqWuUhsTC9P5N18VsoWyztOIVtKRbP7Yy0kqQUZfR2gG3RzhgSaDYIiNb9MTK7hX/exec",
            //     type: "post",
            //     data: $("#submitFormDiduongdai").serializeArray(),
            //     success: function() {
            //         jQuery.notify("Thành công! Chúng tôi sẽ sớm liên hệ", "success");
            //     },
            //     error: function() {
            //         jQuery.notify("Gửi thông tin thất bại", "error");
            //     }
            // });
        }
    });
    $('#submitFormDisanbay').validate({
        rules: {
            "loaixe": {
                required: true,
            },
            "diemdi": {
                required: true,
            },
            "diemden": {
                required: true,
            },
            "ngaydi": {
                required: true,
            }
        },
        messages: {
            "loaixe": {
                required: "Chọn loại xe"
            },
            "diemdi": {
                required: "Tôi đón bạn ở đâu?"
            },
            "diemden": {
                required: "Chọn điểm đến của bạn"
            },
            "ngaydi": {
                required: "Bạn muốn đi hôm nào?"
            }
        },
        errorPlacement: function(error, element) {
            // Tạo hoặc tìm thẻ <span> để hiển thị lỗi
            var errorSpan = $(element).next("span.error-message");
            if (errorSpan.length === 0) {
                errorSpan = $("<span class='error-message w-100'></span>").insertAfter(element);
            }
            errorSpan.text(error.text());
        },
        success: function(label, element) {
            $(element).next("span.error-message").remove(); // Xóa thông báo lỗi khi nhập đúng
        },
        submitHandler: function(form) {
            $.ajax({
                url: "{{ route('get-price') }}?province_id=2",
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#submitFormDisanbay").serializeArray(),
                success: function(response) {
                    $('#popup_price').html(response.price);
                },
                error: function() {
                    jQuery.notify("Gửi thông tin thất bại", "error");
                }
            });
            $('#bookingModal').modal('show');
            // $.ajax({
            //     url: "https://script.google.com/macros/s/AKfycbzltqWuUhsTC9P5N18VsoWyztOIVtKRbP7Yy0kqQUZfR2gG3RzhgSaDYIiNb9MTK7hX/exec",
            //     type: "post",
            //     data: $("#submitFormDisanbay").serializeArray(),
            //     success: function() {
            //         jQuery.notify("Thành công! Chúng tôi sẽ sớm liên hệ", "success");
            //     },
            //     error: function() {
            //         jQuery.notify("Gửi thông tin thất bại", "error");
            //     }
            // });
        }
    });
    $('#rotate_btn').click(function() {
        var diemdi = $('input[name="diemdi"]').val();
        var diemden = $('input[name="diemden"]').val();
        $('input[name="diemdi"]').val(diemden).trigger('change');
        $('input[name="diemden"]').val(diemdi).trigger('change');
    });

    $('#rotate_btn_duongdai').click(function() {
        var diemdi = $('input[name="diemdididuongdai"]').val();
        var diemden = $('input[name="diemdendiduongdai"]').val();
        $('input[name="diemdididuongdai"]').val(diemden).trigger('change');
        $('input[name="diemdendiduongdai"]').val(diemdi).trigger('change');
    });
</script>
