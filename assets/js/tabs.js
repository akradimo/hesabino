// کنترل‌کننده تب‌ها برای فرم‌های چند بخشی
document.addEventListener('DOMContentLoaded', function() {
    // انتخاب همه دکمه‌های تب و محتوای آنها
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // فعال‌سازی تب اول به صورت پیش‌فرض
    showTab('general');
    
    // افزودن رویداد کلیک به همه دکمه‌های تب
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // غیرفعال کردن همه دکمه‌ها
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
                btn.classList.add('text-gray-600', 'hover:text-blue-600');
            });
            
            // فعال‌سازی دکمه انتخاب شده
            button.classList.add('active', 'border-b-2', 'border-blue-500', 'text-blue-600');
            button.classList.remove('text-gray-600', 'hover:text-blue-600');
            
            // نمایش محتوای تب مربوطه
            showTab(button.getAttribute('data-tab'));
        });
    });
});

// تابع نمایش تب انتخاب شده
function showTab(tabId) {
    const tabContents = document.querySelectorAll('.tab-content');
    
    // مخفی کردن همه محتواها
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // نمایش محتوای تب انتخاب شده
    const selectedTab = document.getElementById(tabId + '-tab');
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
    }
}

// تابع تولید بارکد تصادفی 13 رقمی
function generateBarcode() {
    const prefix = '200'; // پیشوند ثابت برای بارکد‌های داخلی
    const random = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
    document.getElementById('barcode').value = prefix + random;
}

// تابع محاسبه مالیات و قیمت نهایی
function calculateTax() {
    const price = parseFloat(document.getElementById('sale_price').value) || 0;
    const taxRate = parseFloat(document.getElementById('sales_tax_rate').value) || 0;
    const taxIncluded = document.getElementById('sales_tax_included').checked;
    const taxAmount = document.getElementById('tax_amount');
    const finalPrice = document.getElementById('final_price');
    
    if (taxIncluded) {
        // اگر قیمت شامل مالیات باشد
        const taxValue = (price * taxRate) / (100 + taxRate);
        taxAmount.value = taxValue.toFixed(2);
        finalPrice.value = price.toFixed(2);
    } else {
        // اگر قیمت بدون مالیات باشد
        const taxValue = (price * taxRate) / 100;
        taxAmount.value = taxValue.toFixed(2);
        finalPrice.value = (price + taxValue).toFixed(2);
    }
}

// تابع بررسی اعتبار فرم قبل از ارسال
function validateForm() {
    const requiredFields = ['name', 'product_code', 'main_unit'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.classList.add('border-red-500');
            isValid = false;
        } else {
            element.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}