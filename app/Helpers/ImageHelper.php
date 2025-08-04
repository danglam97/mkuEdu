<?php


if (!function_exists('get_image_url')) {
    /**
     * Lấy URL ảnh đầy đủ
     *
     * @param string|null $path
     * @return string
     */
    function get_image_url(?string $path): string
    {
        if (!$path) {
            return asset('/style/images/logo-truong.png'); // Ảnh mặc định nếu không có ảnh
        }

        // Nếu là link đầy đủ thì trả về nguyên
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }

        // Trường hợp ảnh nằm trong storage/public
        return asset('storage/' . ltrim($path, '/'));
    }
}
if (!function_exists('get_video_url')){
    function get_video_url(?string $path): ?string
    {
        if (!$path) {
            return null; // Không có video thì trả null
        }

        // Nếu là link đầy đủ thì trả về nguyên (YouTube, Vimeo, file mp4 online...)
        if (preg_match('/^https?:\/\//', $path)) {
            return $path;
        }

        // Nếu là file lưu trong storage/public
        return asset('storage/' . ltrim($path, '/'));
    }

}

