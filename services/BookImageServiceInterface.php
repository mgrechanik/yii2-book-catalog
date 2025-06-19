<?php

declare(strict_types=1);

namespace app\services;

use yii\web\UploadedFile;

interface BookImageServiceInterface
{
    public const DIR_PREFIX = '@app/web';

    public function save(UploadedFile $file): string;

    public function deleteImage($path): void;

}
