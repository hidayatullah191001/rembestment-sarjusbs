<?php

namespace App\Helpers;

use App\Models\MetaApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class MyHelper
{
    public static function ubahFormatTanggal($tanggal)
    {
        $carbonDate = Carbon::parse($tanggal);
        return $carbonDate->format('d F Y');
    }

    public static function ubahFormatTimestamp($timestamp)
    {
        $carbonDate = Carbon::parse($timestamp);
        return $carbonDate->format('d F Y, H:i:s');
    }

    public static function rupiah($angka) {
        $hasil = number_format($angka, 0, ',', '.');
        return $hasil;
    }

    public static function encodeID($id)
    {
        $salt = Config::get('app.key'); // Menggunakan app key sebagai salt
        $encoded = base64_encode($id . '|' . $salt);
        return str_replace(['+', '/', '='], ['-', '_', ''], $encoded); // URL-safe encoding
    }

    public static function decodeID($encodedId)
    {
        $salt = Config::get('app.key'); // Menggunakan app key sebagai salt
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $encodedId));
        $parts = explode('|', $decoded);

        if (count($parts) === 2 && $parts[1] === $salt) {
            return (int) $parts[0]; // Mengembalikan ID asli
        }

        return null; // Return null jika decoding gagal
    }

    public static function getSetting($field)
    {
        return MetaApp::where('field', $field)->value('value');
    }
}
