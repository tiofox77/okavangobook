<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$outputDirectory = $projectRoot . '/public/assets/img/pwa';
$brandingDirectory = $projectRoot . '/public/assets/img/branding';
$sourcePath = $brandingDirectory . '/kiandastay-logo-source.png';

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
    throw new RuntimeException("Não foi possível criar {$outputDirectory}");
}

if (!is_dir($brandingDirectory) && !mkdir($brandingDirectory, 0775, true) && !is_dir($brandingDirectory)) {
    throw new RuntimeException("Não foi possível criar {$brandingDirectory}");
}

function makeWhiteTransparent(\GdImage $image): void
{
    imagealphablending($image, false);
    imagesavealpha($image, true);

    for ($y = 0; $y < imagesy($image); $y++) {
        for ($x = 0; $x < imagesx($image); $x++) {
            $rgba = imagecolorat($image, $x, $y);
            $red = ($rgba >> 16) & 0xFF;
            $green = ($rgba >> 8) & 0xFF;
            $blue = $rgba & 0xFF;

            if ($red >= 245 && $green >= 245 && $blue >= 245) {
                imagesetpixel($image, $x, $y, imagecolorallocatealpha($image, 255, 255, 255, 127));
            }
        }
    }
}

function cropVisible(\GdImage $image, int $padding = 20): \GdImage
{
    $width = imagesx($image);
    $height = imagesy($image);
    $left = $width;
    $top = $height;
    $right = 0;
    $bottom = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $alpha = (imagecolorat($image, $x, $y) >> 24) & 0x7F;
            if ($alpha < 120) {
                $left = min($left, $x);
                $top = min($top, $y);
                $right = max($right, $x);
                $bottom = max($bottom, $y);
            }
        }
    }

    $left = max(0, $left - $padding);
    $top = max(0, $top - $padding);
    $right = min($width - 1, $right + $padding);
    $bottom = min($height - 1, $bottom + $padding);

    return imagecrop($image, [
        'x' => $left,
        'y' => $top,
        'width' => $right - $left + 1,
        'height' => $bottom - $top + 1,
    ]);
}

function saveContainedSquare(\GdImage $source, string $path, int $size, float $contentRatio = 0.82): void
{
    $target = imagecreatetruecolor($size, $size);
    imagealphablending($target, false);
    imagesavealpha($target, true);
    imagefill($target, 0, 0, imagecolorallocatealpha($target, 0, 0, 0, 127));

    $maxContent = (int) round($size * $contentRatio);
    $scale = min($maxContent / imagesx($source), $maxContent / imagesy($source));
    $width = max(1, (int) round(imagesx($source) * $scale));
    $height = max(1, (int) round(imagesy($source) * $scale));
    $x = (int) floor(($size - $width) / 2);
    $y = (int) floor(($size - $height) / 2);

    imagecopyresampled($target, $source, $x, $y, 0, 0, $width, $height, imagesx($source), imagesy($source));
    imagepng($target, $path, 9);
    imagedestroy($target);
}

if (!is_file($sourcePath)) {
    throw new RuntimeException("Fonte do logótipo não encontrada: {$sourcePath}");
}

$source = imagecreatefrompng($sourcePath);
if (!$source) {
    throw new RuntimeException("Imagem inválida: {$sourcePath}");
}

makeWhiteTransparent($source);
$officialLogo = cropVisible($source, 24);
imagepng($officialLogo, $brandingDirectory . '/kiandastay-logo.png', 9);

// O desenho ocupa a parte superior da arte original; o wordmark começa abaixo desta área.
$markRegion = imagecrop($source, [
    'x' => 0,
    'y' => 0,
    'width' => imagesx($source),
    'height' => (int) round(imagesy($source) * 0.70),
]);
$officialMark = cropVisible($markRegion, 18);
imagepng($officialMark, $brandingDirectory . '/kiandastay-mark.png', 9);

saveContainedSquare($officialMark, $projectRoot . '/public/assets/img/icon-512.png', 512);
saveContainedSquare($officialMark, $projectRoot . '/public/assets/img/icon-maskable-512.png', 512, 0.68);
saveContainedSquare($officialMark, $projectRoot . '/public/assets/img/favicon-32.png', 32, 0.94);
saveContainedSquare($officialMark, $projectRoot . '/public/assets/img/favicon-16.png', 16, 0.94);

imagedestroy($officialMark);
imagedestroy($markRegion);
imagedestroy($officialLogo);
imagedestroy($source);

/**
 * @param list<int> $sizes
 */
function generateIcons(string $sourcePath, string $outputDirectory, string $prefix, array $sizes): void
{
    $source = imagecreatefrompng($sourcePath);
    if (!$source) {
        throw new RuntimeException("Imagem inválida: {$sourcePath}");
    }

    imagealphablending($source, true);
    imagesavealpha($source, true);

    foreach ($sizes as $size) {
        $target = imagecreatetruecolor($size, $size);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
        imagefill($target, 0, 0, $transparent);
        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            0,
            0,
            $size,
            $size,
            imagesx($source),
            imagesy($source)
        );

        $path = "{$outputDirectory}/{$prefix}{$size}.png";
        imagepng($target, $path, 9);
        imagedestroy($target);
        echo "{$path}\n";
    }

    imagedestroy($source);
}

generateIcons(
    $projectRoot . '/public/assets/img/icon-512.png',
    $outputDirectory,
    'icon-',
    [72, 96, 128, 144, 152, 180, 192, 384, 512]
);

generateIcons(
    $projectRoot . '/public/assets/img/icon-maskable-512.png',
    $outputDirectory,
    'maskable-',
    [192, 512]
);
