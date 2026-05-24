<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CustomBackground
{
    private const Disk = 'public_images';

    private const Filename = 'site-background';

    /**
     * @var list<string>
     */
    private const Extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'];

    public static function store(UploadedFile $background): string
    {
        $disk = Storage::disk(self::Disk);

        self::delete();

        $extension = strtolower($background->extension() ?: $background->getClientOriginalExtension() ?: 'jpg');

        if (! in_array($extension, self::Extensions, true)) {
            $extension = 'jpg';
        }

        $path = self::Filename.'.'.$extension;
        $disk->putFileAs('', $background, $path);

        return self::urlForPath($path);
    }

    public static function delete(): void
    {
        $disk = Storage::disk(self::Disk);

        foreach (self::Extensions as $extension) {
            $path = self::Filename.'.'.$extension;

            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public static function url(): ?string
    {
        $path = self::path();

        if ($path === null) {
            return null;
        }

        return self::urlForPath($path);
    }

    public static function style(?string $url = null): string
    {
        $backgroundUrl = $url ?? self::url();

        if ($backgroundUrl === null) {
            return '';
        }

        $escapedUrl = str_replace("'", '%27', $backgroundUrl);

        return "background-image: linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('{$escapedUrl}')";
    }

    private static function path(): ?string
    {
        $disk = Storage::disk(self::Disk);

        foreach (self::Extensions as $extension) {
            $path = self::Filename.'.'.$extension;

            if ($disk->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private static function urlForPath(string $path): string
    {
        $disk = Storage::disk(self::Disk);
        $url = asset('images/'.str_replace('\\', '/', $path));

        if (! $disk->exists($path)) {
            return $url;
        }

        return $url.'?v='.$disk->lastModified($path);
    }
}
