# 中间件-内容编码压缩

* 仅限在webman中使用

### 安装仓库

```text
composer require alone-webman/compress
```

### 中间件方法

* 可以任何`config/middleware.php`中使用

```php
return [
    '@' => [alone_mid_compress(array $encoding = ['br', 'gzip', 'deflate'])]
];
```

### 内容编码压缩方法

* 不使用中间件时可以使用此方法

```php
alone_compress(string $encoding, string $body, array $method = []):array;
```