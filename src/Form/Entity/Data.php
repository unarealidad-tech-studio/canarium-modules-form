<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/** 
* @ORM\Entity 
* @ORM\Table(name="form_data")
* @ORM\HasLifecycleCallbacks
*/

class Data {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
	
	
	/**
     * @ORM\Column(type="text",  nullable=true)
     */
	protected $value;
	
	/**
     * @ORM\ManyToOne(targetEntity="Element",inversedBy="data")
	 * @ORM\JoinColumn(name="element_id", referencedColumnName="id", onDelete="CASCADE")
     */
	protected $element;
	
	/**
     * @ORM\ManyToOne(targetEntity="ParentData",inversedBy="data")
	 * @ORM\JoinColumn(name="form_parent_data_id", referencedColumnName="id", onDelete="CASCADE")
     */
	protected $parentdata;

	/**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status;    

    public function getId(){
		return $this->id;
	}
	
	public function getElement(){
		return $this->element;
	}
	
	public function setElement($i){
		$this->element = $i;
	}
	
	public function getParentData(){
		return $this->parentdata;
	}
	
	public function setParentData($i){
		$this->parentdata = $i;
	}

	public function getValue(){
		return $this->value;
	}
	
	public function setValue($i){
		$this->value = $i;
	}
	
	public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}