@extends('master')

@section('title', 'Đặt hàng thành công')

@section('content')
Xin chào {{ $data['order']['first_name'] . $data['order']['last_name'] }} <br><br>

Cảm ơn bạn đã đặt hàng tại website của chúng tôi. <br><br>
Dưới đây là thông tin đơn hàng của bạn: <br><br>

<html>
<table>
    <tr>
        <td>Mã đơn hàng:</td>
        <td>{{ $data['order']['order_no'] }}</td>
    </tr>
    <tr>
        <td>Tên khách hàng:</td>
        <td>{{ $data['order']['first_name'] . $data['order']['last_name'] }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{ $data['order']['email'] }}</td>
    </tr>
    <tr>
        <td>Số điện thoại:</td>
        <td>{{ $data['order']['phone_number'] }}</td>
    </tr>
    <tr>
        <td>Phương thức thanh toán:</td>
        <td>{{ getPaymentMethod($data['order']['payment_method']) }}</td>
    </tr>
    <tr>
        <td>Phí ship:</td>
        <td>{{ number_format($data['order']['delivery_fee']) }} VNĐ</td>
    </tr>
    <tr>
        <td>Thành tiền:</td>
        <td>{{ number_format($data['order']['total']) }} VNĐ</td>
    </tr>
</table>
<br><br>
@foreach ($data['order_items'] as $item)
    Tên sản phẩm: {{$item['product_name']}} <br>
    Số lượng: {{$item['quantity']}} <br>
    Giá: {{ ($item['sale_price'] > 0) && $item['sale_price'] <  $item['origin_price'] ? $item['sale_price'] : $item['origin_price'] }} VNĐ <br>
    Tổng tiền: {{ $item['total']}} VND
    <br><br>
@endforeach

</html>

@endsection