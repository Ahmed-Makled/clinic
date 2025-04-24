<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# نظام إدارة العيادات

نظام متكامل لإدارة العيادات الطبية يسمح بجدولة المواعيد وإدارة الأطباء والمرضى.

## المميزات الرئيسية

- إدارة المواعيد مع اكتشاف التعارضات
- إدارة الأطباء والتخصصات
- إدارة المرضى والملفات الطبية
- نظام المستخدمين والصلاحيات
- واجهة إدارية كاملة

## البنية التقنية

يستخدم النظام:
- Laravel كإطار عمل أساسي
- نمط Repository للوصول للبيانات
- طبقة Service لتنظيم منطق العمل
- نظام وحدات للفصل بين المكونات:
  - Admin: لوحة التحكم
  - Doctors: إدارة الأطباء
  - User: إدارة المستخدمين

## المتطلبات

- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 16
- Composer

## التثبيت

1. نسخ المستودع
```bash
git clone https://github.com/username/clinic.git
```

2. تثبيت اعتماديات PHP
```bash
composer install
```

3. تثبيت اعتماديات Node.js
```bash
npm install
```

4. إنشاء ملف البيئة
```bash
cp .env.example .env
```

5. تهيئة قاعدة البيانات
```bash
php artisan migrate --seed
```

## التوثيق

- `app/Services/README.md`: توثيق طبقة الخدمات
- `app/Repositories/README.md`: توثيق نمط المستودعات
- `app/Traits/README.md`: توثيق السمات المشتركة
- `app/Helpers/README.md`: توثيق الدوال المساعدة
- `app/Docs/AppointmentManagement.md`: توثيق نظام المواعيد

## المساهمة

نرحب بالمساهمات! يرجى قراءة [دليل المساهمة](CONTRIBUTING.md) للتفاصيل حول عملية تقديم التغييرات.

## الترخيص

هذا المشروع مرخص تحت [رخصة MIT](LICENSE).
