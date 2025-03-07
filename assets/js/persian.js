// تبدیل اعداد انگلیسی به فارسی
function تبدیل_به_فارسی(عدد) {
    const اعداد_فارسی = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return عدد.toString().replace(/\d/g, x => اعداد_فارسی[x]);
}

// تبدیل اعداد فارسی به انگلیسی
function تبدیل_به_انگلیسی(عدد) {
    return عدد.toString()
        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
        .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
}

// فرمت‌بندی پول
function فرمت_پول(مبلغ) {
    return تبدیل_به_فارسی(new Intl.NumberFormat('fa-IR').format(مبلغ));
}

// تاریخ شمسی امروز
function تاریخ_امروز() {
    const تاریخ = new Date().toLocaleDateString('fa-IR');
    return تاریخ;
}