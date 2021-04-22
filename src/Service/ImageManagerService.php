<?php

namespace flexycms\FlexyFilemanagerBundle\Service;

use flexycms\FlexyFilemanagerBundle\Entity\FlexyFile;

class ImageManagerService extends FileManagerService implements FileManagerServiceInterface
{
    protected function afterUpload(FlexyFile $file, ?string $mime, string $originalName)
    {
        // Отрезаем расширение, если есть
        $dotPos = strrpos($originalName, '.');
        if ($dotPos !== false) $originalName = substr($originalName, 0, $dotPos);

        $metaData = [
            'title' => $originalName,
            'alt' => $originalName,
            'description' => ''
        ];

        $file->setMetaData($metaData);

        parent::afterUpload($file, $mime, $originalName);
    }

    public function setMetaData($file, $data)
    {
        //dump($data);

        $file->setMetaData($data);
        $this->update($file);
    }


}