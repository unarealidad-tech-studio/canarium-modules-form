<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/**
* @ORM\Entity
* @ORM\Table(name="form")
* @ORM\HasLifecycleCallbacks
*/

class Form {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $name;

	/**
	 * @ORM\OneToMany(targetEntity="Fieldset", mappedBy="form", cascade={"persist"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	protected $fieldset;

	/**
	 * @ORM\OneToMany(targetEntity="\Page\Entity\Page", mappedBy="form", cascade={"persist"})
	 */
	protected $pages;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $label;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $redirectUrl;

	/**
	 * @ORM\ManyToOne(targetEntity="Page\Entity\Page")
	 */
	protected $redirect;

    /**
     * @ORM\Column(type="string",  nullable=true)
     */
    protected $permalink;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     */
    protected $publish;

    /**
     * @ORM\Column(type="string",  nullable=true)
     */
    protected $featuredImage;

	public function __construct()
	{
		$this->fieldset = new ArrayCollection();
		$this->pages = new ArrayCollection();
	}

    public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($i){
		$this->name = $i;
	}
	public function getFieldset(){
		return $this->fieldset;
	}

	public function addFieldset(Collection $ii)
    {
        foreach ($ii as $i) {
			$i->setForm($this);
            $this->fieldset->add($i);
        }
    }

    public function removeFieldset(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setForm(null);
            $this->fieldset->removeFieldset($i);
        }
    }

	public function getPages(){
		return $this->pages;
	}

	public function addPages(Collection $ii)
    {
        foreach ($ii as $i) {
			$i->setForm($this);
            $this->pages->add($i);
        }
    }

    public function removePages(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setForm(null);
            $this->pages->removeFieldset($i);
        }
    }

	public function getLabel(){
		return $this->label;
	}

	public function setLabel($i){
		$this->label = $i;
	}

	public function getRedirectUrl(){
		return $this->redirectUrl;
	}

	public function setRedirectUrl($i){
		$this->redirectUrl = $i;
	}

	public function getRedirect(){
		return $this->redirect;
	}

	public function setRedirect($i){
		$this->redirect = $i;
	}

    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($i)
    {
        $this->permalink = $i;
    }

    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($i)
    {
        $this->publish = $i;
    }

    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage($i)
    {
        $this->featuredImage = $i;
    }
}