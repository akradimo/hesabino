-- ایجاد جدول تنظیمات سیستم
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255),
    company_logo VARCHAR(255),
    company_header TEXT,
    sms_username VARCHAR(100),
    sms_password VARCHAR(100),
    sms_sender VARCHAR(20),
    email_host VARCHAR(255),
    email_username VARCHAR(255),
    email_password VARCHAR(255),
    email_from VARCHAR(255),
    print_template TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    FOREIGN KEY (updated_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول سال‌های مالی
CREATE TABLE fiscal_years (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    is_closed TINYINT(1) DEFAULT 0,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    closed_at DATETIME,
    closed_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (closed_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- بروزرسانی جدول users
ALTER TABLE users 
ADD COLUMN expire_date DATE,
ADD COLUMN signature_image VARCHAR(255),
ADD COLUMN last_login DATETIME,
ADD COLUMN failed_attempts INT DEFAULT 0,
ADD COLUMN locked_until DATETIME,
ADD COLUMN force_change_password TINYINT(1) DEFAULT 0,
ADD COLUMN password_changed_at DATETIME,
ADD COLUMN created_by INT,
ADD COLUMN updated_by INT,
ADD FOREIGN KEY (created_by) REFERENCES users(id),
ADD FOREIGN KEY (updated_by) REFERENCES users(id);

-- ایجاد جدول نقش‌ها
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100),
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول مجوزها
CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    display_name VARCHAR(100),
    description TEXT,
    group_name VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول رابط نقش‌ها و مجوزها
CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- ایجاد جدول رابط کاربران و نقش‌ها
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- درج مجوزهای پیش‌فرض
INSERT INTO permissions (name, display_name, group_name) VALUES
('users.view', 'مشاهده کاربران', 'مدیریت کاربران'),
('users.create', 'ایجاد کاربر', 'مدیریت کاربران'),
('users.edit', 'ویرایش کاربر', 'مدیریت کاربران'),
('users.delete', 'حذف کاربر', 'مدیریت کاربران'),
('roles.view', 'مشاهده نقش‌ها', 'مدیریت نقش‌ها'),
('roles.create', 'ایجاد نقش', 'مدیریت نقش‌ها'),
('roles.edit', 'ویرایش نقش', 'مدیریت نقش‌ها'),
('roles.delete', 'حذف نقش', 'مدیریت نقش‌ها');

-- درج نقش مدیر سیستم
INSERT INTO roles (name, display_name, description) VALUES
('admin', 'مدیر سیستم', 'دسترسی کامل به تمام بخش‌های سیستم');