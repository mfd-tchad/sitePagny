<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Evenement class
 * 
 * @category Class
 * @author   Marie-Françoise Dewulf <marie-francoise@mfdewulf.fr>
 * 
 * @UniqueEntity("titre")
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 * @Vich\Uploadable()
 * 
 */
class Evenement
{
    const TYPE_EVENEMENT = [
        4 => 'Activité associative',
        5 => 'Activité Nature',
        2 => 'Carnet blanc',
        0 => 'Conseil Municipal',
        6 => 'Commémoration',
        3 => 'Festivité',
        7 => 'Flash-Info',
        1 => 'Hommage',
        8 => 'Rencontre sportive'
    ];

    /**
     * Event id generated automatically
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Event type as a number (Cf EVENEMET_TYPE)
     * 
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    /**
     * Date the event takes place
     * 
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Content description for the event
     * 
     * @ORM\Column(type="text")
     */
    private $description = "";

    /**
     * Creation date for the event 
     * 
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var String
     * @Assert\Length(min=4, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * Image File pointer if any
     * 
     * @var File|null
     * @Assert\Image(mimeTypes = {"image/*"})
     * @Vich\UploadableField(mapping="evenement_image",fileNameProperty="image")
     */
    private $imageFile;

    /**
     * Image name if any
     * 
     * @var String|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * Last event update time 
     * 
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updated_at;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /**
     * Get Id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Evenement Type as a number
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get Evenement Type as a full string
     *
     * @param string $type type as a number (cf TYPE_EVENEMENT) not stored in database
     * 
     * @return string|null
     */
    public function getTypeEvenement(string $type): ?string
    {
        return $this::TYPE_EVENEMENT[$type];
    }

    /**
     * Set Evenement Type as a number
     *
     * @param string $type type a s number (Cf EVENEMENT_TYPE) stored in database
     * 
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the date the Evenement takes place
     *
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the date the evenement takes place
     *
     * @param \DateTimeInterface $date event date
     * 
     * @return self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the description for the event
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description for the event
     *
     * @param string $description full content of the article
     * 
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the date the event has been created
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * Set the creation date of the event
     *
     * @param \DateTimeInterface $created_at creation date
     * 
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the event title
     *
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Set the event title
     *
     * @param string $titre the title
     * 
     * @return self
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the image name
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the image name
     *
     * @return self
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get full path file
     * 
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set full path file
     * 
     * @param null|File $imageFile a pointer to the image file
     * 
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

    /**
     * Get slug for event
     *
     * @return string
     */
    public function getSlug(): string
    {
        $slugify = new Slugify();
        return $slugify->slugify($this->getTypeEvenement($this->getType()) . '-' . $this->getTitre());
    }
}
