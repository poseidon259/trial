@extends('master')

@section('title', 'Reset Password')

@section('content')

    Xin chào {{ $data['name'] }} <br><br>

    Anh/Chị đã yêu cầu đổi mật khẩu tại Trial. <br>
    Anh/Chị vui lòng nhập mã xác minh dưới đây để thiết lập lại mật khẩu.<br><br>

    Code: {{ $data['code'] }}<br>

    Lưu ý: Mã xác minh có hiệu lực tối đa 15 phút <br>
@endsection
