# cms
CMS with production multilanguage.

…or create a new repository on the command line
echo "# cms" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/itxeasy/cms.git
git push -u origin main
…or push an existing repository from the command line
git remote add origin https://github.com/itxeasy/cms.git
git branch -M main
git push -u origin main


/your-project-folder
|-- /admin/             # (Sẽ phát triển sau) Trang quản trị
|-- /assets/
|   |-- /css/
|   |-- /js/
|   |-- /images/        # Nơi chứa hình ảnh sản phẩm của bạn
|-- /includes/          # Các tệp PHP tái sử dụng
|   |-- db.php          # Tệp kết nối CSDL
|   |-- header.php      # Phần đầu trang HTML
|   |-- footer.php      # Phần chân trang HTML
|-- /lang/              # Các tệp ngôn ngữ
|   |-- en.php
|   |-- vi.php
|   |-- ar.php
|   |-- ja.php
|-- /templates/         # Giao diện cho các trang
|   |-- home.php
|   |-- products.php
|   |-- product-detail.php
|-- .htaccess           # Tệp cấu hình URL thân thiện
|-- index.php           # Tệp điều hướng chính
|-- config.php          # Tệp cấu hình chung