# https://viblo.asia/p/http-request-methods-su-khac-nhau-co-ban-giua-get-method-va-post-method-L4x5xpEr5BM
# https://viblo.asia/p/html-form-encoding-OeVKBM8Y5kW

HTTP request methods, sự khác nhau cơ bản giữa Get method và Post method
HTTP request methods
Đầu tiên phải nói đến là có tất cả 9 loại request, get và post là 2 loại thông dụng được sử dụng nhiều.

GET: được sử dụng để lấy thông tin từ sever theo URI đã cung cấp.

HEAD: giống với GET nhưng response trả về không có body, chỉ có header

POST: gửi thông tin tới sever thông qua các biểu mẫu http( đăng kí chả hạn..)

PUT: ghi đè tất cả thông tin của đối tượng với những gì được gửi lên

PATCH: ghi đè các thông tin được thay đổi của đối tượng.

DELETE: xóa tài nguyên trên server.

CONNECT: thiết lập một kết nối tới server theo URI.

OPTIONS: mô tả các tùy chọn giao tiếp cho resource.

TRACE: thực hiện một bài test loop - back theo đường dẫn đến resource.

So sánh sự khác nhau giữa GET và POST
vậy sự khác nhau giữa GET và POST như thế nào, chúng ta cùng tìm hiểu nhé.

HTTP POST requests cung cấp dữ liệu từ máy khách (trình duyệt) đến máy chủ trong phần message body. 
Ngược lại,GET request bao gồm tất cả dữ liệu bắt buộc trong URL. 
Các biểu mẫu trong HTML có thể sử dụng một trong hai phương thức bằng cách chỉ định method = "POST" hoặc method = "GET" (mặc định) trong phần tử <form>. 
Phương thức được chỉ định xác định cách dữ liệu biểu mẫu được gửi tới máy chủ. 
Khi phương thức là GET, tất cả dữ liệu biểu mẫu được mã hóa thành URL, được nối vào action URL dưới dạng query string parameters. 
Với POST, dữ liệu biểu mẫu xuất hiện trong phần message body của HTTP request.
