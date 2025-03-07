// تبدیل اعداد انگلیسی به فارسی
function تبدیل_به_فارسی(عدد) {
    const اعداد_فارسی = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return String(عدد).replace(/\d/g, x => اعداد_فارسی[x]);
}

// تبدیل اعداد فارسی به انگلیسی
function تبدیل_به_انگلیسی(عدد) {
    return String(عدد)
        .replace(/[۰-۹]/g, x => '۰۱۲۳۴۵۶۷۸۹'.indexOf(x))
        .replace(/[٠-٩]/g, x => '٠١٢٣٤٥٦٧٨٩'.indexOf(x));
}

// فرمت کردن مبلغ
function فرمت_پول(مبلغ) {
    return تبدیل_به_فارسی(new Intl.NumberFormat('fa-IR').format(مبلغ));
}

// تنظیم خودکار ورودی‌های عددی
document.addEventListener('DOMContentLoaded', () => {
    // تبدیل خودکار اعداد به فارسی
    document.querySelectorAll('input[type="text"], input[type="number"]').forEach(ورودی => {
        ورودی.addEventListener('input', function() {
            this.value = تبدیل_به_فارسی(this.value);
        });
    });

    // فرمت خودکار مبالغ
    document.querySelectorAll('[data-format="money"]').forEach(ورودی => {
        ورودی.addEventListener('blur', function() {
            const مبلغ = تبدیل_به_انگلیسی(this.value);
            if (!isNaN(مبلغ)) {
                this.value = فرمت_پول(مبلغ);
            }
        });
    });
});