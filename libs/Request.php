<?php
namespace libs;

class Request
{
    public static function get($field)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data[$field] ? (gettype($data[$field]) !== 'string' ? $data[$field] : htmlspecialchars($data[$field])) : '';
    }
}
?>