document.addEventListener('DOMContentLoaded', () => {
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

    // رویدادهای محاسبه قیمت و مالیات
    ['قیمت_فروش', 'درصد_مالیات', 'شامل_مالیات'].forEach(فیلد => {
        const المان = document.getElementById(فیلد);
        if (المان) {
            المان.addEventListener('change', محاسبه_قیمت_نهایی);
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

function محاسبه_قیمت_نهایی() {
    const قیمت = parseFloat(تبدیل_به_انگلیسی(document.getElementById('قیمت_فروش').value)) || 0;
    const درصد_مالیات = parseFloat(تبدیل_به_انگلیسی(document.getElementById('درصد_مالیات').value)) || 0;
    const شامل_مالیات = document.getElementById('شامل_مالیات').checked;

    let مبلغ_مالیات = 0;
    let قیمت_نهایی = 0;

    if (شامل_مالیات) {
        مبلغ_مالیات = (قیمت * درصد_مالیات) / (100 + درصد_مالیات);
        قیمت_نهایی = قیمت;
    } else {
        مبلغ_مالیات = (قیمت * درصد_مالیات) / 100;
        قیمت_نهایی = قیمت + مبلغ_مالیات;
    }

    document.getElementById('مبلغ_مالیات').value = فرمت_پول(Math.round(مبلغ_مالیات));
    document.getElementById('قیمت_نهایی').value = فرمت_پول(Math.round(قیمت_نهایی));
}