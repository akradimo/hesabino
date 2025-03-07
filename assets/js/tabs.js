// تابع نمایش تب‌ها
function showTab(tabName) {
    // مخفی کردن همه تب‌ها
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // غیرفعال کردن همه دکمه‌های تب
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });

    // نمایش تب انتخاب شده
    const selectedTab = document.getElementById(`${tabName}-tab`);
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
    }

    // فعال کردن دکمه تب انتخاب شده
    const selectedButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (selectedButton) {
        selectedButton.classList.add('active');
    }
}

// مدیریت کلیک روی دکمه‌های تب با استفاده از Event Delegation
document.addEventListener('DOMContentLoaded', function () {
    // تنظیم تب پیش‌فرض
    showTab('sales');

    // اضافه کردن Event Listener به دکمه‌های تب
    const tabContainer = document.querySelector('.tab-container');
    if (tabContainer) {
        tabContainer.addEventListener('click', function (event) {
            const tabButton = event.target.closest('.tab-button');
            if (tabButton) {
                const tabName = tabButton.getAttribute('data-tab');
                showTab(tabName);
            }
        });
    }
});