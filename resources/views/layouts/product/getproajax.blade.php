@foreach ($products as $item)
@php
    $images = json_decode($item->images);
@endphp
<div class="destination-card" data-sku="{{ $item->sku }}">
    <img src="{{ $images[0] }}" alt="{{ languageName($item->name) }}" loading="lazy">
    <span style="padding-left: 10px; font-weight: 600; font-size: 20px;">{{ languageName($item->name) }}</span>
    <hr>
    <div class="destination-card-content d-flex justify-content-between">
        <p class="mb-0">Starting at <b>{{ formatCurrency($item->min_price_usd, $item->min_price_vnd) }}</b></p>
        <a href="javascript:void(0)" style="color: #009a61;"><i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>
@endforeach
