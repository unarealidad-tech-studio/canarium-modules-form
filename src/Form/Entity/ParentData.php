<?php
namespace Form\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="form_parent_data")
 * @ORM\Entity(repositoryClass="Form\Mapper\ParentData")
 * @ORM\HasLifecycleCallbacks
 */
class ParentData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CanariumCore\Entity\User",inversedBy="forms")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Form\Entity\Data",mappedBy="parentdata",cascade={"persist"})
     */
    protected $data;

    /**
     * @ORM\OneToMany(targetEntity="Form\Entity\Upload",mappedBy="parentdata",cascade={"persist"})
     */
    protected $uploads;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date = null;

    /**
     * @ORM\ManyToOne(targetEntity="Form\Entity\Form")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $form;

    /**
     * @ORM\OneToMany(targetEntity="ParentData", mappedBy="parent", orphanRemoval=true)
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="ParentData", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_updated;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;


    /**
     * @ORM\PrePersist
     **/
    public function onPrePersist()
    {
        $this->date = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     **/
    public function onPreUpdate(){
        $this->setDateUpdated(new \DateTime('now'));
    }

    public function __construct()
    {
        $this->data = new ArrayCollection();
        $this->uploads = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($i)
    {
        $this->children = $i;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(ParentData $i)
    {
        $this->parent = $i;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($i)
    {
        $this->date = $i;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser($i)
    {
        $this->user = $i;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function addData(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setParentData($this);
            $this->data->add($i);
        }
    }

    public function removeData(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setParentData(null);
            $this->data->removeElement($i);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function addUploads(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setParentData($this);
            $this->uploads->add($i);
        }
    }

    public function removeUploads(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setParentData(null);
            $this->uploads->removeElement($i);
        }
    }

    public function getUploads()
    {
        return $this->uploads;
    }

    public function setForm($i)
    {
        $this->form = $i;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    public function setDateUpdated(\DateTime $date_updated)
    {
        $this->date_updated = $date_updated;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }
}