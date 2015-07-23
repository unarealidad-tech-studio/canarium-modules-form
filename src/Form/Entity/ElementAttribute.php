<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/** 
* @ORM\Entity 
* @ORM\Table(name="form_element_attribute")
* @ORM\HasLifecycleCallbacks
*/

class ElementAttribute {
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
     * @ORM\Column(type="text",  nullable=true)
     */
	protected $value;
	
	/**
     * @ORM\ManyToOne(targetEntity="Element",inversedBy="elementattributes")
	 * @ORM\JoinColumn(name="element_id", referencedColumnName="id", onDelete="CASCADE")
     */
	protected $element;
	

    public function getId(){
		return $this->id;
	}
	
	public function getElement(){
		return $this->element;
	}
	
	public function setElement($i){
		$this->element = $i;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($i){
		$this->name = $i;
	}

	public function getValue(){
		return $this->value;
	}
	
	public function setValue($i){
		$this->value = $i;
	}
	
}