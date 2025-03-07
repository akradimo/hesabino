document.addEventListener('DOMContentLoaded', function() {
    // منوی کشویی
    const menuToggles = document.querySelectorAll('.menu-toggle');
    
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            // بستن سایر منوهای باز
            menuToggles.forEach(otherToggle => {
                if (otherToggle !== toggle && otherToggle.classList.contains('active')) {
                    otherToggle.classList.remove('active');
                }
            });
            
            // تغییر وضعیت منوی کلیک شده
            this.classList.toggle('active');
        });
    });

    // باز کردن منوی فعال در لود صفحه
    const activeMenu = document.querySelector('.menu-toggle.active');
    if (activeMenu) {
        activeMenu.click();
    }
});