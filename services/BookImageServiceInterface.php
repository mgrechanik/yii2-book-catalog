<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\services;

use yii\web\UploadedFile;

/**
 * Интерфейс сервиса по работе с картинками
 */
interface BookImageServiceInterface
{
    public const DIR_PREFIX = '@app/web';

    public function save(UploadedFile $file): string;

    public function deleteImage(string $path) : void;

}
