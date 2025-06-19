<?php

declare(strict_types=1);

namespace app\services;

use yii\web\UploadedFile;
use Yii;

class BookImageService implements BookImageServiceInterface
{
    public function save(UploadedFile $file): string
    {
        $pathPrefix = Yii::getAlias(self::DIR_PREFIX);
        $path = '/uploads/' . $file->baseName . '.' . $file->extension;
        // Проверяем, что такой файл существует
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
     * @param $path
     * @return void
     */
    public function deleteImage($path): void
    {
        $path = Yii::getAlias(self::DIR_PREFIX) . $path;
        if (file_exists($path)) {
            unlink($path);
        }
    }

}
