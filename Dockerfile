# ใช้ภาพพื้นฐานจาก php:7.4-apache
FROM php:7.4-apache

# ติดตั้ง mysqli extension
RUN docker-php-ext-install mysqli

# คัดลอกไฟล์จากโฟลเดอร์ public_html ไปที่ /var/www/html
COPY ./public_html/ /var/www/html/

# ตั้งค่า ServerName ให้กับ Apache
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && a2enconf servername

# เปิดใช้งาน mod_rewrite ของ Apache (หากต้องการใช้งาน URL rewriting)
RUN a2enmod rewrite

# รีสตาร์ท Apache เพื่อให้การตั้งค่าใหม่ทำงาน
RUN service apache2 restart
