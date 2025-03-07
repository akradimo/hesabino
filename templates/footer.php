</main>

<!-- فوتر -->
<footer class="bg-white border-t border-gray-200 mt-8">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-500 text-sm">
                © <?php echo date('Y'); ?> حسابینو. تمامی حقوق محفوظ است.
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="/help" class="text-gray-500 hover:text-blue-600 text-sm">
                        <i class="fas fa-question-circle ml-1"></i>
                        راهنما
                    </a>
                    <a href="/contact" class="text-gray-500 hover:text-blue-600 text-sm">
                        <i class="fas fa-envelope ml-1"></i>
                        تماس با ما
                    </a>
                    <a href="/about" class="text-gray-500 hover:text-blue-600 text-sm">
                        <i class="fas fa-info-circle ml-1"></i>
                        درباره ما
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- اسکریپت‌های جاوااسکریپت -->
<script>
    // مدیریت منوی کاربر
    document.getElementById('user-menu-button')?.addEventListener('click', function() {
        const منوی_کاربر = document.getElementById('user-menu');
        منوی_کاربر?.classList.toggle('hidden');
    });

    // بستن منوی کاربر با کلیک خارج از آن
    document.addEventListener('click', function(رویداد) {
        const دکمه_منو = document.getElementById('user-menu-button');
        const منوی_کاربر = document.getElementById('user-menu');
        
        if (دکمه_منو && منوی_کاربر && !دکمه_منو.contains(رویداد.target) && !منوی_کاربر.contains(رویداد.target)) {
            منوی_کاربر.classList.add('hidden');
        }
    });

    // نمایش تاریخ و ساعت فارسی
    function بروزرسانی_ساعت() {
        const تاریخ = new Date();
        const ساعت = تاریخ.toLocaleTimeString('fa-IR');
        if (document.getElementById('ساعت')) {
            document.getElementById('ساعت').textContent = ساعت;
        }
    }

    setInterval(بروزرسانی_ساعت, 1000);
</script>
</body>
</html>