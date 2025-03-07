</main>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/js/all.min.js"></script>
<script src="<?= asset('js/scripts.js') ?>"></script>
    <!-- پاورقی -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                <?= 'تمامی حقوق برای حسابینو محفوظ است ' . تاریخ_شمسی('Y') ?>
            </div>
        </div>
    </footer>

    <!-- نمایش پیام‌های خطا -->
    <?php if(isset($_SESSION['خطا'])): ?>
    <div id="پیام_خطا" class="fixed bottom-4 left-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg">
        <?= $_SESSION['خطا'] ?>
        <?php unset($_SESSION['خطا']); ?>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('پیام_خطا').remove();
        }, 5000);
    </script>
    <?php endif; ?>

    <!-- نمایش پیام‌های موفقیت -->
    <?php if(isset($_SESSION['موفقیت'])): ?>
    <div id="پیام_موفقیت" class="fixed bottom-4 left-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg">
        <?= $_SESSION['موفقیت'] ?>
        <?php unset($_SESSION['موفقیت']); ?>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('پیام_موفقیت').remove();
        }, 5000);
    </script>
    <?php endif; ?>
</body>
</html>