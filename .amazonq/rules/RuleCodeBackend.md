Nguyên tắc cốt lõi
Mỗi class chỉ làm đúng một việc. Controller không chứa logic, Service không query DB, Repository không xử lý nghiệp vụ. Vi phạm nguyên tắc này là nguồn gốc của mọi code rối.

Đặt tên
Tên phải nói lên được mục đích, không cần comment giải thích. Đọc tên là hiểu ngay làm gì.
Class dùng PascalCase, luôn có suffix nói rõ vai trò: BookingService, HotelRepository, StoreBookingRequest. Không đặt tên chung chung như HotelManager, BookingHelper vì không ai biết nó làm gì.
Method dùng camelCase, bắt đầu bằng động từ: getAvailableRooms, createBookingSession, cancelExpiredBookings. Tránh handle, process, doSomething vì quá mơ hồ.
Variable dùng camelCase, đặt tên theo nội dung thực sự chứa bên trong: $availableRooms, $expiredSession, $confirmedBooking. Tuyệt đối không dùng $data, $result, $temp, $arr.
Boolean variable phải bắt đầu bằng is, has, can, should: $isAvailable, $hasExpired, $canBook.

Controller
Controller chỉ làm ba việc: nhận request, gọi service, trả response. Không viết query, không viết if lồng nhau, không chứa business logic. Mỗi method tối đa 15 dòng. Nếu vượt quá, logic đó phải xuống Service.

Service
Service chứa toàn bộ business logic. Khi một method bắt đầu dài, tách ra thành các private method nhỏ có tên rõ ràng. Mỗi private method làm đúng một bước, đặt tên mô tả bước đó. Tổng một method public không quá 30 dòng thực sự, phần còn lại phân rã xuống private. Tuyệt đối không gọi Model::where() trực tiếp trong Service, mọi truy vấn đi qua Repository.

Repository
Repository chỉ chứa query. Không có if nghiệp vụ, không có tính toán, không throw exception nghiệp vụ. Trả về Model hoặc Collection hoặc null, để Service quyết định xử lý tiếp. Method tên phải nói rõ điều kiện query: findActiveByUserId, findWithLockForUpdate, findExpiredSessions.

Form Request
Toàn bộ validation phải nằm trong FormRequest, không validate trong Controller hay Service. Rules phải đầy đủ kiểu dữ liệu, giới hạn độ dài, kiểm tra tồn tại trong DB nếu cần. Messages trả về tiếng Việt rõ ràng, không để Laravel tự generate message tiếng Anh mặc định cho user thấy.

Enum
Không dùng số nguyên hay chuỗi cứng rải rác trong code. Mọi trạng thái, loại, phân loại phải là Enum. Enum phải có method label() trả về tên hiển thị để dùng trong UI. Dùng Enum trực tiếp khi gán giá trị, không gán số hay string thô.

Exception
Mỗi loại lỗi nghiệp vụ phải có Exception riêng, không dùng chung Exception. Tên Exception mô tả đúng tình huống: BookingExpiredException, RoomNotAvailableException, ConfigChangedException. Handler tập trung tại app/Exceptions/Handler.php, không try catch rải rác khắp nơi rồi return message thủ công.

XSS
Blade template luôn dùng {{ }}, không bao giờ dùng {!! !!} trừ khi nội dung đã qua HTMLPurifier. Mọi input từ user phải được sanitize trước khi lưu DB, đặt trong Mutator của Model để tự động áp dụng, không sanitize thủ công rải rác. Security headers phải được set qua Middleware áp dụng toàn bộ route, không set từng route một.

Database
Mọi thao tác liên quan đến tiền, số lượng, trạng thái quan trọng phải nằm trong DB::transaction(). Khi cần tránh race condition phải dùng lockForUpdate(). Không bao giờ dùng $model->quantity -= 1 rồi save, phải dùng decrement() để atomic. Migration phải có comment giải thích cột nào dùng để làm gì nếu tên chưa đủ rõ.

Quy tắc bất di bất dịch
Một function không quá 100 dòng, lý tưởng dưới 30. Nếu phải đếm dòng để biết có vượt không, function đó đã cần tách rồi. Không comment giải thích code làm gì, đặt tên đúng thì không cần comment. Chỉ comment khi giải thích lý do tại sao làm vậy, không phải làm gì. Không để code chết, không để biến không dùng, không để import thừa. Mỗi lần sửa file phải để lại sạch hơn lúc mở ra.
Quy tắc đặt file
Một Model       → Một Service → Một Repository → Một nhóm Request
Hotel.php       → HotelService.php → HotelRepository.php → Hotel/Store, Update...
Booking.php     → BookingService.php → BookingRepository.php → Booking/Store, Init...

Tên file = Tên class bên trong
Folder = Nhóm tính năng, không nhóm theo kiểu file

Exports/        → Class định nghĩa xuất Excel ra file
Imports/        → Class định nghĩa đọc Excel vào DB
Mail/           → Class định nghĩa nội dung email
Jobs/           → Chạy nền: gửi mail, export Excel
views/emails/   → Template HTML của email

Nguyên tắc:
  Excel nặng   → Jobs → Queue
  Email        → Events → Listeners → Jobs → Queue
  Không bao giờ gửi mail hay export thẳng trong Controller


- Dùng type hint đầy đủ Important
- Có PHPDoc mỗi method Important
- Clean code, không có logic thừa Important
