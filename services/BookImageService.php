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
use Yii;

/**
 * Сервис по управлению картинками
 */
class BookImageService implements BookImageServiceInterface
{
    /**
     * Сохраняем загруженную картинку
     * @param UploadedFile $file
     * @return string Относительный путь  сохраненной картинки
     */
    public function save(UploadedFile $file): string
    {
        $pathPrefix = Yii::getAlias(self::DIR_PREFIX);
        $path = '/uploads/' . $file->baseName . '.' . $file->extension;
        // Проверяем, если такой файл существует
        if (file_exists($pathPrefix . $path)) {
            $i = 1;
            while (1) {
                $i++;
                $path = $path = '/uploads/' . $file->baseName . '-' . $i . '.' . $file->extension;
                if (!file_exists($pathPrefix . $path)) {
                    break;
                }
            }
        }
        $file->saveAs($pathPrefix . $path);
        return $path;
    }

    /**
     * Удаляем картинку
     *
     * @param string $path Путь сохраненной в БД картинки
     * @return void
     */
    public function deleteImage(string $path): void
    {
        if (!$path) {
            return;
        }
        $path = Yii::getAlias(self::DIR_PREFIX) . $path;
        if (file_exists($path)) {
            unlink($path);
        }
    }

}
