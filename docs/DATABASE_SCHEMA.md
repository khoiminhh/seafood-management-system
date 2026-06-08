# Sơ Đồ Cơ Sở Dữ Liệu

## Tổng Quan
Hệ thống sử dụng 18 bảng chính để quản lý toàn bộ hoạt động kinh doanh.

## Các Bảng Chính

### 1. Users (Người Dùng)
- `id`: ID duy nhất
- `role`: admin, staff, customer
- `status`: active, inactive, blocked

### 2. Categories (Danh Mục)
- Phân loại: Cá, Tôm, Cua, Ngao Sò, Đồ khô, Đồ đông lạnh, Hải sản chế biến

### 3. Products (Sản Phẩm)
- `storage_type`: live, frozen, chilled, ready_to_eat
- `base_unit`: kg, piece, bag, tray, box

### 4. Inventory (Kho Hàng)
- Quản lý batch & expiry date
- FIFO tracking
- Status: in_stock, low_stock, expired, damaged

### 5. Wastage (Hao Hụt)
- Lý do: suffocation, death, weight_loss, trimming, damaged, expired
- Theo dõi % hao hụt hàng ngày

### 6. Orders (Đơn Hàng)
- 2-step process cho hàng cân ký
- Status: pending -> confirmed -> weighing -> processing -> shipping -> delivered

### 7. Daily_Prices (Giá Ngày)
- Cập nhật giá nhanh theo buổi (sáng, chiều, tối)

### 8. Financial_Reports (Báo Cáo Tài Chính)
- Revenue, COGS, Gross Profit
- Wastage value tracking

## Mối Quan Hệ Chính

```
Categories
    |
    └── Products
            |
            ├── Variants
            ├── Inventory
            ├── Daily_Prices
            └── Order_Items
                    |
                    └── Orders
                            |
                            └── Customers

Suppliers
    ├── Supplier_Prices
    └── Inventory

Wastage
    └── Inventory & Products
```
