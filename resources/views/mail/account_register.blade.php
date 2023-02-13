@extends('master')

@section('title', 'Account Register')

@section('content')
Xin chào {{ $data['name'] }} <br><br>

Cảm ơn bạn đã thực hiện thao tác đăng ký tài khoản tại Trial. <br>
Vui lòng truy cập vào link dưới đây để hoàn thành thủ tục đăng ký tài khoản. <br><br>

URL：<a href={{ $data['url'] }}> {{ $data['url'] }} </a><br><br>

Lưu ý: Link URL có hiệu lực tối đa 15 phút <br>

@endsection