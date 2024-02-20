<?php

class HeaderContent {
    public static function set($contentType="") {
        $validTypes = ["javascript", "css", "json", "php", "python", "text", "html"];
        $contentTypeLower = strtolower($contentType);
        
        if (in_array($contentTypeLower, $validTypes)) {
            $contentTypeHeader = 'Content-Type: ';
            switch ($contentTypeLower) {
                case 'javascript':
                    $contentTypeHeader .= 'application/javascript';
                    break;
                case 'css':
                    $contentTypeHeader .= 'text/css';
                    break;
                case 'json':
                    $contentTypeHeader .= 'application/json';
                    break;
                case 'php':
                    $contentTypeHeader .= 'text/php'; // Assuming PHP script will output HTML
                    break;
                case 'python':
                    $contentTypeHeader .= 'text/python'; // Not a standard MIME type, adjust accordingly
                    break;
                case 'text':
                    $contentTypeHeader .= 'text/plain';
                    break;
                case 'html':
                    $contentTypeHeader .= 'text/html';
                    break;
                default:
                    $contentTypeHeader .= 'text/plain';
            }
            header($contentTypeHeader);
        } else {
            // Invalid content type
            header('Content-Type: text/plain');
        }
    }
}
