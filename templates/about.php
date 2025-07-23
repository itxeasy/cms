<?php
// File: templates/about.php
// -------------------------
// Template cho trang Giới thiệu.
// Nội dung được lấy cảm hứng từ file index.html của bạn.

global $lang;
?>

<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-blue-700 mb-4"><?= $lang['nav_about'] ?></h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Một công ty mới thành lập được dẫn dắt bởi các chuyên gia dày dạn kinh nghiệm với hàng thập kỷ kinh nghiệm tổng hợp trong thương mại Đông Nam Á.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <img src="https://cdn.prod.website-files.com/64ef6bac7b8ca0e71e05ec87/65691d496c73ea3ca98762d0_Webflow%20(2).png" 
                         alt="[Hình ảnh về Logistics Việt Nam]" 
                         class="rounded-2xl shadow-2xl w-full h-auto">
                </div>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Tầm nhìn của chúng tôi</h3>
                            <p class="text-gray-600">Trở thành cầu nối đáng tin cậy nhất, kết nối các nhà sản xuất Đông Nam Á với thị trường toàn cầu, tận dụng vị trí chiến lược của Việt Nam và kinh nghiệm sâu rộng của đội ngũ chúng tôi.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-target text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Sứ mệnh của chúng tôi</h3>
                            <p class="text-gray-600">Tạo điều kiện thuận lợi cho thương mại quốc tế liền mạch bằng cách cung cấp các giải pháp hiệu quả, đáng tin cậy và sáng tạo, tạo ra giá trị vượt trội cho các đối tác và khách hàng trên toàn thế giới.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





