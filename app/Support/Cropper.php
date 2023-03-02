<?php

namespace LaraDev\Support;

class Cropper
{
    public static function thumb(string $uri, int $width, int $height = null)
    {
        $crooper = new \CoffeeCode\Cropper\Cropper('../public/storage/cache');
        $pathThumb = $crooper->make(config('filesystems.disks.public.root') . '/' . $uri, $width, $height);

        $file = 'cache/' . collect(explode('/', $pathThumb))->last();

        return $file;
    }

    public static function flush(?string $path)
    {
        $crooper = new \CoffeeCode\Cropper\Cropper('../public/storage/cache');

        if(!empty($path)){
            $crooper->flush($path);
        }else{
            $crooper->flush();
        }
    }
}
