<?php

if (!isset($_GET['svg'])) {
    http_response_code(404);
    exit;
}
$svgName = $_GET['svg'];
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $svgName)) {
    http_response_code(404);
    exit;
}
$svgFilePath = __DIR__ . "/images/" . $svgName . ".svg";

if (!is_file($svgFilePath)) {
    http_response_code(404);
    exit;
}
$svgContent = file_get_contents($svgFilePath);
if ($svgContent === false) {
    http_response_code(404);
    exit;
}
$forbiddenPatterns = [
    '<script',
    '</script',
    'onload=',
    'onclick=',
    'onbegin=',
    'onmouseover=',
    'onfocus=',
    'javascript:',
    '<foreignobject',
    '<iframe',
    '<embed',
    '<object',
    '<link',
    'xlink:href'
];

foreach ($forbiddenPatterns as $pattern) {
    if (stripos($svgContent, $pattern) !== false) {
        http_response_code(404);
        echo 'Protected SVG';
        exit;
    }
}

header('Content-Type: image/svg+xml; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; img-src data:;");
header('Referrer-Policy: no-referrer');

echo $svgContent;
exit;
