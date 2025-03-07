// مدیریت تب‌ها و رویدادها
document.addEventListener('DOMContentLoaded', function() {
    // متغیرهای سراسری
    const تب_ها = {
        دکمه_ها: document.querySelectorAll('.tab-button'),
        محتواها: document.querySelectorAll('.tab-content')
    };

    // فعال‌سازی تب اول در شروع
    فعال_کردن_تب_اول();

    // اضافه کردن رویداد کلیک به تب‌ها
    تب_ها.دکمه_ها.forEach(دکمه => {
        دکمه.addEventListener('click', () => تغییر_تب(دکمه));
    });

    // تولید بارکد خودکار
    const دکمه_بارکد = document.querySelector('[data-action="generate-barcode"]');
    if (دکمه_بارکد) {
        دکمه_بارکد.addEventListener('click', تولید_بارکد_خودکار);
    }

    // رویدادهای محاسبه مالیات
    ['sale_price', 'sales_tax_rate', 'sales_tax_included'].forEach(فیلد => {
        const المان = document.getElementById(فیلد);
        if (المان) {
            المان.addEventListener('change', محاسبه_مالیات);
        }
    });
});

// توابع اصلی
function فعال_کردن_تب_اول() {
    const تب_اول = document.querySelector('.tab-button');
    if (تب_اول) {
        تغییر_تب(تب_اول);
    }
}

function تغییر_تب(دکمه_فعال) {
    // غیرفعال کردن همه تب‌ها
    document.querySelectorAll('.tab-button').forEach(دکمه => {
        دکمه.classList.remove('active', 'border-blue-500', 'text-blue-600');
        دکمه.classList.add('text-gray-700', 'border-transparent');
    });

    // فعال کردن تب انتخاب شده
    دکمه_فعال.classList.add('active', 'border-blue-500', 'text-blue-600');
    دکمه_فعال.classList.remove('text-gray-700', 'border-transparent');

    // نمایش محتوای تب
    const شناسه_تب = دکمه_فعال.getAttribute('data-tab');
    document.querySelectorAll('.tab-content').forEach(محتوا => محتوا.classList.add('hidden'));
    document.getElementById(شناسه_تب + '-tab')?.classList.remove('hidden');
}

function تولید_بارکد_خودکار() {
    const پیشوند = '200';
    const بخش_تصادفی = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
    document.getElementById('barcode').value = پیشوند + بخش_تصادفی;
}

function محاسبه_مالیات() {
    const قیمت = parseFloat(document.getElementById('sale_price').value) || 0;
    const نرخ_مالیات = parseFloat(document.getElementById('sales_tax_rate').value) || 0;
    const شامل_مالیات = document.getElementById('sales_tax_included').checked;

    let مبلغ_مالیات = 0;
    let قیمت_نهایی = 0;

    if (شامل_مالیات) {
        مبلغ_مالیات = (قیمت * نرخ_مالیات) / (100 + نرخ_مالیات);
        قیمت_نهایی = قیمت;
    } else {
        مبلغ_مالیات = (قیمت * نرخ_مالیات) / 100;
        قیمت_نهایی = قیمت + مبلغ_مالیات;
    }

    // نمایش نتایج با فرمت فارسی
    if (document.getElementById('tax_amount')) {
        document.getElementById('tax_amount').value = new Intl.NumberFormat('fa-IR').format(Math.round(مبلغ_مالیات));
    }
    if (document.getElementById('final_price')) {
        document.getElementById('final_price').value = new Intl.NumberFormat('fa-IR').format(Math.round(قیمت_نهایی));
    }
}