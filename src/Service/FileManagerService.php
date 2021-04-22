<?php

namespace flexycms\FlexyFilemanagerBundle\Service;

use flexycms\FlexyFilemanagerBundle\Entity\FlexyFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $manager;
    private $fileRepository;
    private $uploadPath;

    public function __construct(string $uploadPath, EntityManagerInterface $manager)
    {
        $this->uploadPath = $uploadPath;
        $this->manager = $manager;
        $this->fileRepository = $manager->getRepository(FlexyFile::class);
    }

    /**
     * Вовращает полный путь до папки uploads от корня сервера
     * @return string
     */
    private function getUploadDirectory() {
        return $_SERVER['DOCUMENT_ROOT'] . $this->getUploadPath();
    }

    /**
     * Вовращает путь до папки uploads от корня сайта
     * @return string
     */
    private function getUploadPath() {
        return $this->uploadPath;
    }


    /**
     * Получает объект файла из имени файла. Если файла не существует на диске или в базе - возвращает null
     * @param ?string $fileName
     * @return FlexyFile|null
     */
    public function getFile(?string $fileName): ?FlexyFile
    {
        if ($fileName == null) return null;
        $file = $this->findByName($fileName);

        if (!is_file($this->getUploadDirectory() . '/' . $fileName)) return null;
        if (!$file) return null;

        return $file;
    }


    /**
     * Синхронизирует запрошенный файл с базой.
     * Если есть на диске, но нет в базе - заносит в базу данные по файлу
     * Если есть в базе, но нет на диске - удаляет из базы
     * @param string $fileName
     * @return FlexyFile|null
     */
    public function synchronize(string $fileName)
    {
        $file = $this->findByName($fileName);

        if (!is_file($this->getUploadDirectory() . '/' . $fileName))
        {
            // Файла на диске нет, удаляем из базы
            $this->manager->remove($file);
            $this->manager->flush();
            return null;
        }

        if (!$file)
        {
            // Файл на диске есть, а в базе нет. Добавляем
            $file = new FlexyFile();
            $this->manager->persist($file);

            $file->setName($fileName);
            $file->setUploadAt();
            $this->update($file);
        }

        // В базе (теперь) тоже есть, возвращаем
        return $file;
    }



    /**
     * Загружает файл на диск, заносит его в базу
     * @param UploadedFile $uploadedFile
     * @return mixed
     */
    public function upload(UploadedFile $uploadedFile): ?FlexyFile
    {
        $extension = $uploadedFile->guessExtension();
        $extension = $extension ? '.' . $extension : '';
        $now = new DateTime();
        $path = $now->format("Y/m");
        $dir = $this->getUploadPath() . '/' . $path;

        $mime = $uploadedFile->getMimeType();
        $originalName = htmlspecialchars($uploadedFile->getClientOriginalName());

        // Формируем случайное имя
        do {
            $name = substr(md5(microtime() . rand(0, 9999)), 0, 8) . $extension;
            $filePath = $dir .  $name;
        } while (file_exists($filePath));

        try {
            $uploadedFile->move($_SERVER["DOCUMENT_ROOT"] . $dir, $name);
        }
        catch(FileException $ex) {
            return null;
        }

        $file = new FlexyFile();
        $this->manager->persist($file);

        $file->setName($path . '/' . $name);

        $this->afterUpload($file, $mime, $originalName);

        return $file;

    }

    /**
     *
     * Вызывается после загрузки файла.
     *
     * @param FlexyFile $file
     * @param string|null $mime
     * @param string $originalName
     */
    protected function afterUpload(FlexyFile $file, ?string $mime, string $originalName)
    {
        $file->setMimeType($mime);
        $this->update($file);
    }



    /**
     * Обновляет данные по файлу в базе. Наличие файла на диске не проверяется - для синхронизации пользуйтесь методом synchronize
     * @param FlexyFile $file
     * @return $this
     */
    public function update(FlexyFile $file): FileManagerServiceInterface
    {
        // Обновляем данные в базе
        $size = filesize($this->getUploadDirectory() . '/' . $file->getName());
        $file->setSize($size);

        $this->manager->flush();
        return $this;
    }





    public function delete(FlexyFile $file)
    {
        $filePath = $this->getUploadDirectory() . '/' . $file->getName();
        if (is_file($filePath)) unlink($filePath);
        $this->manager->remove($file);
    }

    public function findByName($value): ?FlexyFile
    {
        return $this->fileRepository->findOneBy(['name' => $value]);
    }

}