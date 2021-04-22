<?php

namespace flexycms\FlexyFilemanagerBundle\Service;

use flexycms\FlexyFilemanagerBundle\Entity\FlexyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerServiceInterface
{
    /**
     * Загружает файл на диск, заносит его в базу
     * @param UploadedFile $uploadedFile
     * @return mixed
     */
    public function upload(UploadedFile $uploadedFile): ?FlexyFile;

    /**
     * Получает файл с диска, если нет - удаляет соответствующую запись из базы
     * Проверяет, есть ли такой файл в базе, если нет - добавляет запись
     * @param string $fileName
     * @return FlexyFile|null
     */
    public function getFile(string $fileName): ?FlexyFile;

    /**
     * Обновляет данные файла в базе. Если записи в базе нет - создаёт её
     * @param FlexyFile $file
     * @return $this
     */
    public function update(FlexyFile $file): FileManagerServiceInterface;


    /**
     * @param FlexyFile $file
     * @return null
     */
    public function delete(FlexyFile $file);


    /**
     * Возвращает объект File по имени файла
     * Имя файла включает в себя поддиректории от папки /uploads
     * @param $value
     * @return FlexyFile|null
     */
    public function findByName($value): ?FlexyFile;



    /**
     * Синхронизирует запрошенный файл с базой.
     * Если есть на диске, но нет в базе - заносит в базу данные по файлу
     * Если есть в базе, но нет на диске - удаляет из базы
     * @param string $fileName
     * @return FlexyFile|null
     */
    public function synchronize(string $fileName);

}