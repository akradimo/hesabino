// مدیریت تب‌ها و عملیات محصولات
document.addEventListener('DOMContentLoaded', function() {
    // انتخاب همه دکمه‌های تب
    const دکمه_های_تب = document.querySelectorAll('.tab-button');
    
    // فعال کردن تب اول به صورت پیش‌فرض
    const تب_اول = document.querySelector('.tab-button');
    if (تب_اول) {
        تب_اول.click();
    }
    
    // اضافه کردن رویداد کلیک به هر دکمه تب
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // حذف کلاس فعال از همه دکمه‌ها
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('text-gray-600', 'border-transparent');
            });
            
            // فعال کردن دکمه کلیک شده
            button.classList.add('active', 'border-blue-500', 'text-blue-600');
            button.classList.remove('text-gray-600', 'border-transparent');
            
            // نمایش محتوای تب مربوطه
            const tabId = button.getAttribute('data-tab');
            const allTabContents = document.querySelectorAll('.tab-content');
            allTabContents.forEach(content => content.classList.add('hidden'));
            
            const selectedTab = document.getElementById(tabId + '-tab');
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
        });
    });

    // تولید خودکار بارکد محصول
    const barcodeBtn = document.querySelector('[data-action="generate-barcode"]');
    if (barcodeBtn) {
        barcodeBtn.addEventListener('click', () => {
            const prefix = '200'; // پیشوند ثابت برای بارکدهای داخلی
            const randomPart = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
            document.getElementById('barcode').value = prefix + randomPart;
        });
    }

    // محاسبه خودکار مالیات
    const calculateTaxElements = ['sale_price', 'sales_tax_rate', 'sales_tax_included'];
    calculateTaxElements.forEach(elementId => {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', calculateTaxAndTotal);
        }
    });

    // تبدیل واحد به صورت خودکار
    const conversionElements = ['main_unit', 'sub_unit', 'conversion_factor'];
    conversionElements.forEach(elementId => {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', handleUnitConversion);
        }
    });
});

// محاسبه مالیات و قیمت نهایی
function calculateTaxAndTotal() {
    const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
    const taxRate = parseFloat(document.getElementById('sales_tax_rate').value) || 0;
    const isTaxIncluded = document.getElementById('sales_tax_included').checked;
    
    let taxAmount = 0;
    let totalPrice = 0;
    
    if (isTaxIncluded) {
        // محاسبه مالیات از قیمت شامل مالیات
        taxAmount = (salePrice * taxRate) / (100 + taxRate);
        totalPrice = salePrice;
    } else {
        // محاسبه مالیات و اضافه کردن به قیمت پایه
        taxAmount = (salePrice * taxRate) / 100;
        totalPrice = salePrice + taxAmount;
    }
    
    // نمایش نتایج در فرم
    if (document.getElementById('tax_amount')) {
        document.getElementById('tax_amount').value = taxAmount.toFixed(0);
    }
    if (document.getElementById('total_price')) {
        document.getElementById('total_price').value = totalPrice.toFixed(0);
    }
}

// مدیریت تبدیل واحدها
function handleUnitConversion() {
    const mainUnit = document.getElementById('main_unit').value;
    const subUnit = document.getElementById('sub_unit').value;
    const factor = document.getElementById('conversion_factor');
    
    // اگر واحد اصلی و فرعی یکسان باشند
    if (mainUnit === subUnit) {
        factor.value = '1';
        factor.setAttribute('readonly', true);
    } else {
        factor.removeAttribute('readonly');
    }
}

// اعتبارسنجی فرم قبل از ارسال
function validateProductForm() {
    const requiredFields = [
        { id: 'name', message: 'نام محصول الزامی است' },
        { id: 'product_code', message: 'کد محصول الزامی است' },
        { id: 'main_unit', message: 'واحد اصلی الزامی است' }
    ];
    
    let isValid = true;
    const errorContainer = document.getElementById('form-errors');
    errorContainer.innerHTML = '';
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        const value = element.value.trim();
        
        if (!value) {
            isValid = false;
            errorContainer.innerHTML += `<div class="text-red-500 text-sm mb-1">${field.message}</div>`;
            element.classList.add('border-red-500');
        } else {
            element.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}