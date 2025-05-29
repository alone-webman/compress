<?php

namespace AloneWebMan\Compress;

class Mid {
    public array $aloneEncoding = [];

    public function __construct(array $encoding = ['br', 'gzip', 'deflate']) {
        $this->aloneEncoding = $encoding;
    }

    public function process($request, $next) {
        $encoding = $request->header('accept-encoding');
        $response = $next($request);
        if (!empty($encoding)) {
            $body = $response->rawBody();
            if (!empty($body)) {
                $compress = alone_compress($encoding, $body, $this->aloneEncoding);
                if (!empty($type = ($compress['type'] ?? '')) && !empty($body = ($compress['body'] ?? ''))) {
                    return $response->header('Content-Encoding', $type)->withBody($body);
                }
            }
        }
        return $response;
    }
}