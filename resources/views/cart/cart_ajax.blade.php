@if(count($cart) > 0)
<table class="cart-table table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>Product</th>
            <th style="width: 20%;">Quantity</th>
            <th style="width: 20%;">Price</th>
            <th style="width: 10%;">Action</th>
        </tr>
    </thead>
    <tbody id="cart-table-body">
        @php
            $total_usd = 0;
            $total_vnd = 0;
        @endphp
        @foreach($cart as $item)
        <tr>
            <td>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <img src="{{ isset($item['image']) ? url(''.$item['image']) : url('frontend/images/no-image.png')}}" alt="" style="width: 50px; height: 50px; border-radius: 5px;">
                    <span><b>{{ isset($item['product_name']) ? languageName($item['product_name']) : ''}}</b><br><span class="text-muted">{{($item['name'])}}</span></span>
                </div>
            </td>
            <td>
                <div class="quantity-input d-flex align-items-center">
                    <button class="quantity-button quantity-button-minus btn btn-secondary">-</button>
                    <input type="number" class="form-control" value="{{$item['quantity']}}" min="1" max="1000" data-id="{{$item['idOption']}}" >
                    <button class="quantity-button quantity-button-plus btn btn-secondary">+</button>
                </div>
            </td>
            <td><b>{{formatCurrency($item['price_usd'], $item['price_vnd'])}}</b></td>
            <td>
                <a href="javascript:void(0)" class="btn btn-secondary btn-remove-cart" data-id="{{$item['idOption']}}"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @php
            $total_usd += $item['price_usd'] * $item['quantity'];
            $total_vnd += $item['price_vnd'] * $item['quantity'];
        @endphp
        @endforeach
    </tbody>
</table>
<div class="cart-total mt-5">
    <div class="row">
        <div class="col-md-8">
            <a href="{{route('home')}}" class="btn btn-primary"> <i class="fa fa-arrow-left"></i> Continue Shopping</a>
        </div>
        <div class="col-md-4">
            <h3 class="text-right">Total: {{formatCurrency($total_usd, $total_vnd)}}</h3>
            <a href="{{route('checkout')}}" class="btn btn-primary">Checkout <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</div>
@else
<div class="cart-empty-message">
    <p>Your cart is currently empty.</p>
    <a href="{{route('home')}}" class="btn btn-primary">Continue Shopping</a>
</div>
@endif