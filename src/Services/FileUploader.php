<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 */
class FileUploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string|null $targetDirectory
     */
    public function __construct(?string $targetDirectory)
    {
        $this->setTargetDirectory($targetDirectory);
    }

    /**
     * @param UploadedFile $file
     * @param string|null  $path
     *
     * @return string
     */
    public function upload(UploadedFile $file, ?string $path = null): string
    {
        if (!is_null($path)) {
            $this->setTargetDirectory($path);
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );
        $fileName = $safeFilename.'-'.date('d_m_Y_His').'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return '';
        }

        return $fileName;
    }

    /**
     * Remove uploaded file.
     *
     * @param string $path
     */
    public function removeFileUploaded(string $path)
    {
        try {
            if ($path) {
                unlink($path);
            }
        } catch (\Exception $e) {
            //nothing yet
        }
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @param $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        if (!is_dir($this->targetDirectory)) {
            if (!mkdir($this->targetDirectory, 0777, true) && !is_dir($this->targetDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $this->targetDirectory));
            }
            chmod($this->targetDirectory, 0777);
        }
    }

    /**
     * @return string
     */
    public static function generateUniqueFileName(): string
    {
        return md5(uniqid('', true));
    }

    /**
     * so many times, filename has a space or special characters, this is needle for cleanup them
     *
     * @param string|null $filename
     *
     * @return string
     */
    public function cleanFileName(?string $filename): string
    {
        return ltrim($filename);
    }
}
