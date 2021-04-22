<?php

namespace flexycms\FlexyFilemanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`file`")
 */
class FlexyFile
{

    public function __construct($name = null)
    {
        if ($name !== null) $this->setName($name);
        $this->setUploadAt(new \DateTime());
        $this->setMetaData([]);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaData;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploadAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMetaData(): array
    {
        return json_decode($this->metaData, true);
    }

    public function setMetaData(array $metaData): self
    {
        $this->metaData = json_encode($metaData);
        return $this;
    }


    public function __get($name)
    {
        return $this->getMetaDataValue($name);
    }
    public function __set($name, $value)
    {
        $this->setMetaDataValue($name, $value);
    }




    public function getMetaDataValue(string $name)
    {
        $data = $this->getMetaData();
        if (isset($data[$name])) return $data[$name];
        return '';
    }
    public function setMetaDataValue(string $name, $value)
    {
        $data = $this->getMetaData();
        $data[$name] = $value;
        $this->setMetaData($data);

    }


    public function getUploadAt(): ?\DateTimeInterface
    {
        return $this->uploadAt;
    }

    public function setUploadAt(\DateTimeInterface $uploadAt = null): self
    {
        if ($uploadAt === null) $uploadAt = new \DateTime();
        $this->uploadAt = $uploadAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     */
    public function setMimeType($mimeType): void
    {
        $this->mimeType = $mimeType;
    }

}
