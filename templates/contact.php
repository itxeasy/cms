<?php
// File: templates/contact.php
// ---------------------------
// Template cho trang Liên hệ.

global $lang;

// Kiểm tra xem có thông báo trạng thái từ việc gửi form không
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-blue-700 mb-4"><?= $lang['contact_form_title'] ?></h1>
                <p class="text-xl text-gray-600"><?= $lang['contact_form_subtitle'] ?></p>
            </div>

            <div class="bg-gray-50 p-8 rounded-2xl shadow-lg">
                <?php if ($status == 'success'): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Thành công!</p>
                        <p><?= $lang['contact_success'] ?></p>
                    </div>
                <?php elseif ($status == 'error'): ?>
                     <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Lỗi!</p>
                        <p><?= $lang['contact_error'] ?></p>
                    </div>
                <?php endif; ?>

                <form action="<?= SITE_URL ?>/contact-handler.php" method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?= $lang['full_name'] ?> *</label>
                            <input type="text" name="full_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?= $lang['email'] ?> *</label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?= $lang['message'] ?> *</label>
                        <textarea name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    <input type="hidden" name="lang" value="<?= CURRENT_LANG ?>">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg transition-all">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <?= $lang['send_message'] ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
