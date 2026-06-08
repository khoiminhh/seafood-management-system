# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
Mỗi request cần header:
```
Authorization: Bearer {token}
Content-Type: application/json
```

## Endpoints

### Products (Sản Phẩm)

#### GET /products
Lấy danh sách sản phẩm

**Query Parameters:**
- `category_id`: ID danh mục
- `storage_type`: live, frozen, chilled, ready_to_eat
- `page`: Trang (default: 1)
- `per_page`: Số item/trang (default: 20)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "sku": "CA001",
      "name": "Cá hồi",
      "category": "Cá",
      "storage_type": "live",
      "price": 250000,
      "variants": [
        {"type": "Nguyên con", "adjustment": 0},
        {"type": "Phi lê", "adjustment": 50000}
      ]
    }
  ]
}
```

#### POST /products
Tạo sản phẩm mới

**Body:**
```json
{
  "sku": "CA002",
  "name": "Cá chép",
  "category_id": 1,
  "base_price": 120000,
  "storage_type": "live",
  "base_unit": "kg"
}
```

### Inventory (Kho Hàng)

#### GET /inventory?product_id=1
Lấy tồn kho sản phẩm

#### POST /inventory
Nhập kho hàng

**Body:**
```json
{
  "product_id": 1,
  "batch_number": "BATCH20240608001",
  "quantity_in": 50.5,
  "unit_of_measure": "kg",
  "date_received": "2024-06-08 08:00:00",
  "expiry_date": "2024-06-15 23:59:59",
  "supplier_id": 1,
  "location": "Bể A1"
}
```

#### POST /inventory/wastage
Ghi nhận hao hụt

**Body:**
```json
{
  "inventory_id": 1,
  "quantity": 2.5,
  "reason": "suffocation",
  "description": "Cá bị ngộp do mất điện"
}
```

### Orders (Đơn Hàng)

#### POST /orders
Tạo đơn hàng mới

**Body:**
```json
{
  "customer_id": 1,
  "order_type": "online",
  "items": [
    {
      "product_id": 1,
      "quantity": 2.5,
      "variant_id": 1,
      "processing_services": ["trim", "clean"]
    }
  ],
  "delivery_date_required": "2024-06-08 18:00:00",
  "shipping_provider": "ahamove",
  "delivery_address": "123 Đường ABC, HCM",
  "delivery_phone": "0901234567"
}
```

#### PATCH /orders/{id}/weight
Cập nhật trọng lượng thực tế (step 2)

**Body:**
```json
{
  "actual_weight": 2.8,
  "items": [
    {"item_id": 1, "actual_quantity": 2.8}
  ]
}
```

Hệ thống sẽ tự động:
- Tính toán giá mới dựa trên trọng lượng thực tế
- Gửi SMS/Zalo thông báo cho khách
- Cập nhật trạng thái đơn hàng

#### GET /orders/{id}
Lấy chi tiết đơn hàng

### Reports (Báo Cáo)

#### GET /reports/daily?date=2024-06-08
Báo cáo doanh thu ngày

**Response:**
```json
{
  "total_revenue": 5000000,
  "total_cogs": 2500000,
  "gross_profit": 2500000,
  "gross_margin": 50,
  "total_wastage_value": 100000,
  "total_orders": 45,
  "bestsellers": [
    {"product": "Cá hồi", "quantity": 45.5, "revenue": 1137500}
  ]
}
```

## Error Responses

```json
{
  "success": false,
  "message": "Error message",
  "errors": {"field": ["error details"]}
}
```

## Status Codes
- `200`: OK
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Unprocessable Entity
- `500`: Internal Server Error
