# AI Music Module for Existing Plugin

Module này dùng để nhúng vào **plugin WordPress có sẵn** của bạn, không phải một plugin độc lập.

## Cấu trúc
- `module-loader.php`: file bootstrap để include vào plugin chính
- `includes/`: toàn bộ class PHP
- `assets/`: JS/CSS cho widget frontend

## Cách tích hợp vào plugin hiện có

### 1) Chép folder này vào plugin của bạn
Ví dụ:
```php
your-existing-plugin/
├─ your-existing-plugin.php
├─ modules/
│  └─ ai-music-module/
│     ├─ module-loader.php
│     ├─ includes/
│     └─ assets/
```

### 2) Trong file plugin chính, include loader
```php
require_once plugin_dir_path(__FILE__) . 'modules/ai-music-module/module-loader.php';

\YourPlugin\AI_Music\Module_Loader::bootstrap(
    plugin_dir_path(__FILE__) . 'modules/ai-music-module/',
    plugin_dir_url(__FILE__) . 'modules/ai-music-module/',
    '1.0.0'
);
```

### 3) Trong activation hook của plugin chính, gọi activate()
```php
register_activation_hook(__FILE__, function () {
    \YourPlugin\AI_Music\Module_Loader::activate();
});
```

## Yêu cầu
- WordPress
- WooCommerce
- Action Scheduler (thường có sẵn cùng WooCommerce)
- OpenAI API key
- Nên có `ffmpeg` trên server nếu muốn cắt preview 30 giây trước khi gửi AI

## Menu admin được thêm
- WooCommerce > AI Music Settings
- WooCommerce > AI Music Monitor

## Endpoint frontend
- `POST /wp-json/aim/v1/widget-recommend`

Body mẫu:
```json
{
  "prompt": "Epic dark fantasy boss fight trailer with strong build-up",
  "limit": 6
}
```

## Widget frontend tối giản
Bạn có thể render HTML sau ở shortcode / Elementor widget của plugin bạn:
```html
<div class="aim-widget" data-limit="6">
  <textarea class="aim-widget-prompt" placeholder="Describe your video context..."></textarea>
  <button type="button" class="aim-widget-submit">Find Matching Music</button>
  <div class="aim-widget-status"></div>
  <div class="aim-widget-results"></div>
</div>
```

Nhớ enqueue:
- script handle: `aim-widget-frontend`
- style handle: `aim-widget-frontend`

## Ghi chú
- Module này chỉ hỗ trợ audio `mp3` và `wav`
- Nếu không có `ffmpeg`, module sẽ fallback gửi file gốc thay vì clip preview
- Các mảng meta được lưu dưới dạng JSON string trong post meta của `product`
