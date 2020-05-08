<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 * @Vich\Uploadable()
 */
class Evenement
{
   
    const TYPE_EVENEMENT = [
        0 => 'Naissance',
        1 => 'Hommage',
        2 => 'Mariage',
        3 => 'Fête',
        4 => 'Vide-Greniers',
        5 => 'Ile aux Enfants',
        6 => 'Commémoration',
        7 => 'Concert',
        8 => 'Animation',
        9 => 'Repas',
        10 => 'Sport',
        11 => 'Flash-Info'
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes = {"image/*"})
     * 
     * @Vich\UploadableField(mapping="evenement_image",fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @var String|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updated_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getTypeEvenement(string $type): ?string
    {
        return $this::TYPE_EVENEMENT[$type];
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File
     * @return Evenement
     */
    public function setImageFile(File $imageFile = null): self
    {
        $this->imageFile = $imageFile;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($imageFile) {
            // if 'updated_at' is not defined in your entity, use another property
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getSlug() {
        $slugify = new Slugify();
        return $slugify->slugify($this->getTypeEvenement($this->getType()) . $this->getTitre());
    }


}
