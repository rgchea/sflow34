<?php

namespace Solucel\AdminBundle\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Operator
 */
class Operator
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $description;


    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $daysToFixDevice = '1';
	
   /**
     * Image file
     *
     * @var File
     *
     * @Assert\File(
     *     maxSize = "1M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 1MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    protected $file;	



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Operator
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Operator
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Operator
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
		
    }
	

    public function setCreatedAtValue()
    {
        // Add your code here
        $this->createdAt = new \DateTime();
	
    }		

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set daysToFixDevice
     *
     * @param integer $daysToFixDevice
     *
     * @return Operator
     */
    public function setDaysToFixDevice($daysToFixDevice)
    {
        $this->daysToFixDevice = $daysToFixDevice;

        return $this;
    }

    /**
     * Get daysToFixDevice
     *
     * @return integer
     */
    public function getDaysToFixDevice()
    {
        return $this->daysToFixDevice;
    }
	
	public function __toString(){
		return $this->getName();
	}		
    /**
     * @var boolean
     */
    private $enabled = true;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Operator
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * @var string
     */
    private $logoPath;


    /**
     * Set logoPath
     *
     * @param string $logoPath
     *
     * @return Operator
     */
    public function setLogoPath($logoPath)
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
     * Get logoPath
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }


    //upload images
    //upload images
    //upload images
    
    /**
     * Called before saving the entity
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logoPath = $filename.'.'.$this->file->guessExtension();
        }
    }
    /**
     * Called after entity persistence
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
    	
		
        // The file property can be empty if the field is not required
        if (null === $this->file) {
        	
            return;
        }
        //print $this->logoPath;die;
        // Use the original file name here but you should
        // sanitize it at least to avoid any security issues
        
        // move takes the target directory and then the
        // target filename to move to
        $this->file->move($this->getUploadRootDir(), $this->logoPath);
        
        // Set the path property to the filename where you've saved the file
        //$this->path = $this->file->getClientOriginalName();
        
        // Clean up the file property as you won't need it anymore
        $this->file = null;
    }
    
    /**
     * Called before entity removal
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
    
    public function getAbsolutePath()
    {
        return null === $this->logoPath ? null : $this->getUploadRootDir().'/'.$this->logoPath;
    }
    
    public function getWebPath()
    {
        return null === $this->logoPath
        ? null
        : $this->getUploadDir().'/'.$this->logoPath;
    }
    
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
        // get rid of the DIR so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/operator_logos/';
    }
    
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (isset($this->logoPath)) {
            unlink($this->getAbsolutePath());
        }
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
   
    
    
}
