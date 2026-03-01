app/
├── Console/
├── Exceptions/
├── Exports/
├── Imports/
├── Mail/
├── Jobs/
├── Events/
├── Listeners/
├── Notifications/
├── Traits/
├── Helpers/
├── Enums/
├── Models/
├── Services/
├── Repositories/
│   └── Contracts/
├── Providers/
└── Http/
    ├── Controllers/
    │   ├── Web/
    │   └── Api/
    │       └── V1/
    ├── Middleware/
    ├── Requests/
    └── Resources/

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   └── emails/
├── js/
└── css/

routes/
├── web.php
├── api.php
└── channels.php

config/
database/
├── migrations/
├── seeders/
└── factories/

storage/
tests/
├── Feature/
└── Unit/
Models/          → Định nghĩa Model, relationship, mutator
Services/        → Toàn bộ business logic
Repositories/    → Chỉ query DB, không có logic
Contracts/       → Interface của Repository
Enums/           → Thay thế magic string/number
Exceptions/      → Custom exception theo từng nghiệp vụ
Exports/         → Định nghĩa xuất Excel
Imports/         → Định nghĩa đọc Excel vào DB
Mail/            → Định nghĩa nội dung email
Jobs/            → Tác vụ chạy nền (gửi mail, export...)
Events/          → Sự kiện xảy ra sau action chính
Listeners/       → Bắt Event, dispatch Job
Notifications/   → Push notification, SMS, email alert
Traits/          → Code dùng chung nhiều class
Helpers/         → Global function tiện ích
Providers/       → Bind Interface vào Implementation
Controllers/     → Nhận request, gọi Service, trả response
Requests/        → Validate input
Resources/       → Transform data trước khi trả API
Middleware/      → Xử lý request/response xuyên suốt
views/layouts/   → Template layout chung
views/components/→ Component blade tái sử dụng
views/emails/    → Template HTML email
Feature/         → Test theo luồng nghiệp vụ
Unit/            → Test từng function nhỏ
