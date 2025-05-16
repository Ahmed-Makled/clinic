<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# نظام إدارة العيادات الطبية

نظام متكامل لإدارة العيادات الطبية يسمح بحجز المواعيد وإدارة الأطباء والمرضى.

## الوثائق التقنية

يحتوي المشروع على وثائق تقنية شاملة يمكن الوصول إليها من خلال ملف `Documentation.html`.

### تحديث الوثائق

لتحديث الوثائق التقنية مع أحدث الرسومات البيانية، استخدم الأمر التالي:

```bash
./update_documentation.sh
```

هذا النص البرمجي سيقوم بتحويل جميع رسومات Mermaid و PlantUML إلى صور بجودة عالية وتحديث ملف الوثائق.

#### جودة الصور

تم تعزيز جودة الصور والمخططات البيانية بالوثائق من خلال:

1. صور **PNG بدقة عالية** - تم تعديل خيارات الإنشاء لتوليد صور PNG بمقياس 3x وجودة 100%
2. صور **SVG متجهية** - يتم إنشاء نسخة متجهية (SVG) من كل مخطط للحصول على أعلى جودة ممكنة بغض النظر عن حجم العرض
3. **تحسين الصور** - تم تحسين جودة وحجم صور PNG باستخدام أداة pngquant

#### أدوات توليد المخططات

الوثائق تعتمد على نوعين من المخططات البيانية:

* **مخططات Mermaid**: يمكن إنشاء إصدارات PNG أو SVG باستخدام:
  ```bash
  ./build_mermaid_diagrams.sh     # إنشاء مخططات PNG عالية الدقة
  ./build_mermaid_diagrams_svg.sh # إنشاء مخططات SVG متجهية
  ```

* **مخططات PlantUML**: يمكن إنشاء إصدارات PNG أو SVG باستخدام:
  ```bash
  ./build_diagrams.sh            # إنشاء مخططات PNG
  ./build_puml_diagrams_svg.sh   # إنشاء مخططات SVG متجهية
  ```

* **تحسين صور PNG**:
  ```bash
  ./optimize_png_images.sh       # تحسين جودة وحجم صور PNG
  ```

#### تحديث الوثائق بالكامل

لتحديث جميع المخططات وتوليد كل من صور PNG و SVG مع دمجها في ملف الوثائق النهائي:

```bash
./update_documentation.sh
```

## المميزات الرئيسية

- **نظام حجز المواعيد**: حجز المواعيد وإلغائها مع التحقق من توفر الأوقات
- **إدارة الأطباء**: تسجيل الأطباء وتخصصاتهم وجداولهم
- **إدارة المرضى**: تسجيل وإدارة بيانات المرضى
- **المدفوعات**: دعم الدفع الإلكتروني عبر Stripe
- **لوحة التحكم**: واجهة للإدارة والتحكم في النظام

## هيكل المشروع

يعتمد المشروع على نظام الوحدات النمطية (Modules) في Laravel، مقسم كالتالي:

### الوحدات الأساسية:
- **Core**: المكونات الأساسية والمشتركة
- **Auth**: نظام المصادقة وتسجيل الدخول
- **Users**: إدارة المستخدمين والأدوار والصلاحيات

### وحدات الأعمال الطبية:
- **Appointments**: إدارة المواعيد والحجوزات
- **Doctors**: إدارة بيانات الأطباء وجداولهم
- **Patients**: إدارة بيانات المرضى وسجلاتهم
- **Specialties**: إدارة التخصصات الطبية

### وحدات الواجهة والتفاعل:
- **Dashboard**: لوحة التحكم
- **Payments**: نظام المدفوعات
- **Contacts**: نظام التواصل مع الإدارة

## المتطلبات التقنية

- PHP >= 8.1
- MySQL / MariaDB
- Composer
- Node.js & npm

## التثبيت والإعداد

1. استنساخ المشروع
```bash
git clone https://github.com/your-username/clinic.git
```

2. تثبيت اعتماديات PHP
```bash
composer install
```

3. نسخ ملف الإعدادات
```bash
cp .env.example .env
```

4. إنشاء مفتاح التطبيق
```bash
php artisan key:generate
```

5. إعداد قاعدة البيانات
```bash
php artisan migrate --seed
```

6. تثبيت وبناء الواجهة
```bash
npm install && npm run build
```

7. تشغيل المشروع
```bash
php artisan serve
```

## تسجيل الدخول

- **المسؤول**: admin@example.com / كلمة المرور: password
- **الطبيب**: doctor@example.com / كلمة المرور: password
- **المريض**: patient@example.com / كلمة المرور: password

## الميزات المتقدمة

- **نظام الإشعارات**: إشعارات فورية للمواعيد والتحديثات
- **التقارير**: تقارير متنوعة حول المواعيد والمدفوعات
- **البحث المتقدم**: البحث عن الأطباء حسب التخصص والمنطقة والتقييم
- **واجهة متعددة اللغات**: دعم اللغة العربية والإنجليزية

## المستقبل التطويري للمشروع

- إضافة نظام الاستشارات عن بعد
- تطوير تطبيقات الهاتف المحمول
- إضافة خدمة الوصفات الطبية الإلكترونية
- تكامل مع أنظمة التأمين الطبي

## شكر وتقدير

شكر خاص للدكتور [اسم المشرف] على الإشراف والتوجيه، وجميع أعضاء فريق المشروع لجهودهم.
