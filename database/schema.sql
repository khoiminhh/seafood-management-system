-- Hệ Thống Quản Lý Hải Sản - Database Schema
-- MySQL 8.0+

CREATE DATABASE IF NOT EXISTS seafood_db;
USE seafood_db;

-- ============================================
-- 1. BẢNG NGƯỜI DÙNG
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- ============================================
-- 2. BẢNG DANH MỤC SẢN PHẨM
-- ============================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

-- ============================================
-- 3. BẢNG SẢN PHẨM
-- ============================================
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    storage_type ENUM('live', 'frozen', 'chilled', 'ready_to_eat') DEFAULT 'live',
    origin VARCHAR(100),
    base_unit ENUM('kg', 'piece', 'bag', 'tray', 'box') DEFAULT 'kg',
    base_price DECIMAL(12, 2) NOT NULL,
    status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
    min_stock DECIMAL(10, 3),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_sku (sku),
    INDEX idx_category (category_id),
    INDEX idx_status (status)
);

-- ============================================
-- 4. BẢNG BIẾN THỂ SẢN PHẨM
-- ============================================
CREATE TABLE variants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    variant_type VARCHAR(50),
    variant_value VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    price_adjustment DECIMAL(8, 2) DEFAULT 0,
    sku_suffix VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_variant (product_id, variant_type, variant_value),
    INDEX idx_product (product_id)
);

-- ============================================
-- 5. BẢNG DANH SÁCH DỊCH VỤ CHỂ BIẾN
-- ============================================
CREATE TABLE processing_services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    fee DECIMAL(8, 2) DEFAULT 0,
    is_free BOOLEAN DEFAULT false,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 6. BẢNG KHO HÀNG (INVENTORY)
-- ============================================
CREATE TABLE inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    batch_number VARCHAR(50) NOT NULL,
    quantity_in DECIMAL(10, 3) NOT NULL,
    quantity_current DECIMAL(10, 3) NOT NULL,
    unit_of_measure VARCHAR(20),
    date_received DATETIME NOT NULL,
    expiry_date DATETIME,
    supplier_id INT,
    location VARCHAR(100),
    status ENUM('in_stock', 'low_stock', 'expired', 'damaged', 'sold_out') DEFAULT 'in_stock',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_batch (batch_number),
    INDEX idx_expiry (expiry_date),
    INDEX idx_status (status)
);

-- ============================================
-- 7. BẢNG HẠO HỤT / MẤT HÀNG
-- ============================================
CREATE TABLE wastage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inventory_id INT,
    product_id INT NOT NULL,
    quantity DECIMAL(10, 3) NOT NULL,
    reason ENUM(
        'suffocation',
        'death',
        'weight_loss',
        'trimming',
        'damaged',
        'expired',
        'other'
    ) NOT NULL,
    description TEXT,
    recorded_by INT,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id),
    INDEX idx_product (product_id),
    INDEX idx_reason (reason)
);

-- ============================================
-- 8. BẢNG NHÀ CUNG CẤP
-- ============================================
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    city VARCHAR(100),
    supplier_type ENUM('farm', 'market', 'boat', 'distributor') DEFAULT 'market',
    status ENUM('active', 'inactive') DEFAULT 'active',
    credit_limit DECIMAL(15, 2),
    current_debt DECIMAL(15, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

-- ============================================
-- 9. BẢNG LỊCH SỬ GIÁ NHẬP
-- ============================================
CREATE TABLE supplier_prices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    product_id INT NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    date_from DATE NOT NULL,
    date_to DATE,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_date (date_from, date_to)
);

-- ============================================
-- 10. BẢNG ĐƠN HÀNG
-- ============================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    order_type ENUM('online', 'pos', 'wholesale') DEFAULT 'online',
    status ENUM(
        'pending',
        'confirmed',
        'weighing',
        'processing',
        'shipping',
        'delivered',
        'cancelled',
        'complaint'
    ) DEFAULT 'pending',
    subtotal DECIMAL(15, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(15, 2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    payment_method ENUM('cod', 'bank_transfer', 'momo', 'vnpay', 'cash') DEFAULT 'cod',
    delivery_address TEXT,
    delivery_phone VARCHAR(20),
    delivery_date_required DATETIME,
    estimated_delivery DATETIME,
    actual_delivery DATETIME,
    actual_weight DECIMAL(10, 3),
    actual_total DECIMAL(15, 2),
    shipping_provider VARCHAR(50),
    tracking_number VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created (created_at)
);

-- ============================================
-- 11. BẢNG CHI TIẾT ĐƠN HÀNG
-- ============================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity DECIMAL(10, 3) NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    processing_services JSON,
    processing_fee DECIMAL(8, 2) DEFAULT 0,
    original_total DECIMAL(15, 2) NOT NULL,
    actual_quantity DECIMAL(10, 3),
    actual_total DECIMAL(15, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (variant_id) REFERENCES variants(id),
    INDEX idx_order (order_id)
);

-- ============================================
-- 12. BẢNG GIỎ HÀNG TẠM (POS)
-- ============================================
CREATE TABLE pos_carts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    total_items INT DEFAULT 0,
    subtotal DECIMAL(15, 2) DEFAULT 0,
    discount DECIMAL(10, 2) DEFAULT 0,
    tax DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(15, 2) DEFAULT 0,
    status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id)
);

-- ============================================
-- 13. BẢNG CHI TIẾT GIỎ POS
-- ============================================
CREATE TABLE pos_cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pos_cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity DECIMAL(10, 3) NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    line_total DECIMAL(15, 2) NOT NULL,
    processing_services JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pos_cart_id) REFERENCES pos_carts(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_cart (pos_cart_id)
);

-- ============================================
-- 14. BẢNG KHÁCH HÀNG / CRM
-- ============================================
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    phone VARCHAR(20) UNIQUE,
    email VARCHAR(100),
    full_name VARCHAR(255) NOT NULL,
    address TEXT,
    city VARCHAR(100),
    loyalty_points INT DEFAULT 0,
    total_spent DECIMAL(15, 2) DEFAULT 0,
    total_orders INT DEFAULT 0,
    last_order_date DATETIME,
    status ENUM('active', 'inactive') DEFAULT 'active',
    marketing_consent BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_phone (phone),
    INDEX idx_email (email)
);

-- ============================================
-- 15. BẢNG GIÁ NGÀY (DAILY PRICING)
-- ============================================
CREATE TABLE daily_prices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    price_date DATE NOT NULL,
    time_period ENUM('morning', 'afternoon', 'evening') DEFAULT 'morning',
    reason VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_daily_price (product_id, price_date, time_period),
    INDEX idx_date (price_date)
);

-- ============================================
-- 16. BẢNG BÁO CÁO TÀI CHÍNH
-- ============================================
CREATE TABLE financial_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_date DATE NOT NULL,
    total_revenue DECIMAL(15, 2),
    total_cogs DECIMAL(15, 2),
    gross_profit DECIMAL(15, 2),
    gross_margin_percent DECIMAL(5, 2),
    total_wastage_value DECIMAL(15, 2),
    total_orders INT,
    avg_order_value DECIMAL(12, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_date (report_date),
    INDEX idx_date (report_date)
);

-- ============================================
-- 17. BẢNG SẢN PHẨM BÁN CHẠY
-- ============================================
CREATE TABLE bestsellers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_date DATE NOT NULL,
    product_id INT NOT NULL,
    quantity_sold DECIMAL(10, 3),
    revenue DECIMAL(15, 2),
    rank INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_bestseller (report_date, product_id),
    INDEX idx_date (report_date)
);

-- ============================================
-- 18. BẢNG VOUCHER / KHUYẾN MÃI
-- ============================================
CREATE TABLE vouchers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percent', 'fixed') DEFAULT 'percent',
    discount_value DECIMAL(10, 2) NOT NULL,
    min_purchase DECIMAL(10, 2) DEFAULT 0,
    max_usage INT,
    current_usage INT DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_to DATE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_code (code),
    INDEX idx_status (status)
);

CREATE INDEX idx_search ON products(name, sku);
CREATE INDEX idx_inventory_search ON inventory(product_id, batch_number, status);
