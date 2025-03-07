-- ایجاد جدول محصولات
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,0) DEFAULT 0,
    stock INT DEFAULT 0,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول مشتریان
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    mobile VARCHAR(11) DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول فاکتورها
CREATE TABLE IF NOT EXISTS invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(20) NOT NULL,
    customer_id INT NOT NULL,
    total_amount DECIMAL(10,0) DEFAULT 0,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول اقلام فاکتور
CREATE TABLE IF NOT EXISTS invoice_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10,0) DEFAULT 0,
    total_price DECIMAL(10,0) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- اضافه کردن داده‌های نمونه به جدول مشتریان
INSERT INTO customers (name, mobile, email, address) VALUES
('علی محمدی', '09123456789', 'ali@example.com', 'تهران، خیابان ولیعصر'),
('مریم احمدی', '09198765432', 'maryam@example.com', 'اصفهان، خیابان چهارباغ');

-- اضافه کردن داده‌های نمونه به جدول محصولات
INSERT INTO products (code, name, price, stock) VALUES
('P001', 'لپ تاپ ایسوس', 25000000, 5),
('P002', 'موس گیمینگ', 850000, 10),
('P003', 'کیبورد مکانیکال', 1200000, 8);