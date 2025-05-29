<?php

use AloneWebMan\Compress\Mid;

/**
 * 压缩中间件
 * @param array $encoding 压缩类型,也可以传入一个回调函数,返回压缩后的内容,回调函数的参数为$body
 * @return Mid
 */
function alone_mid_compress(array $encoding = ['br', 'gzip', 'deflate']): Mid {
    return new Mid($encoding);
}

if (!function_exists('alone_compress')) {
    /**
     * @param string $encoding
     * @param string $body
     * @param array  $method
     * @return array|string[]
     */
    function alone_compress(string $encoding, string $body, array $method = []): array {
        $accept = array_map('trim', explode(',', strtolower($encoding)));
        $aloneMethod = [
            'br'      => function($body) {
                return function_exists('brotli_compress') ? brotli_compress($body, 6) : null;
            },
            'gzip'    => function($body) {
                return function_exists('gzencode') ? gzencode($body, 6) : null;
            },
            'deflate' => function($body) {
                return function_exists('gzdeflate') ? gzdeflate($body, 6) : null;
            }
        ];
        if (!empty($method)) {
            foreach ($method as $k => $v) {
                if (is_callable($v) && ($v instanceof Closure)) {
                    if (in_array($k, $accept) && !empty($res = $v($body))) {
                        return ['type' => $k, 'body' => $res];
                    }
                } elseif (in_array($v, $accept) && !empty($val = ($aloneMethod[$v] ?? '')) && !empty($res = $val($body))) {
                    return ['type' => $v, 'body' => $res];
                }
            }
        } else {
            foreach ($aloneMethod as $key => $val) {
                if (in_array($key, $accept) && !empty($res = $val($body))) {
                    return ['type' => $key, 'body' => $res];
                }
            }
        }
        return ['type' => '', 'body' => $body];
    }
}