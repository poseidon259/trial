
<html>
    <br> Xin chào {{ $data['name'] }}<br>
    <br>
    Bạn đã được tạo tài khoản để truy cập Hệ thống Quản lý của Nhà vận hành Trial.<br>
    Vui lòng click vào link dưới đây để truy cập hệ thống:<br><br>
    URL：<a href={{ $data['url'] }}> {{ $data['url'] }} </a><br><br>
    
    <p>------------------------------------------------</p><br>
    Thông tin tài khoản: <br>
    Email đăng nhập:  {{ $data['email'] }} <br>
    Mật khẩu: {{ $data['password'] }} <br>
    Username: {{ $data['user_name'] }} <br>
    Số điện thoại: {{ $data['phone_number'] }} <br>
    Để bảo mật thông tin, vui lòng không chia sẻ với đối tượng không liên quan.<br>
</html>