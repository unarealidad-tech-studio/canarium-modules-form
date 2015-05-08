<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/** 
* @ORM\Entity 
* @ORM\Table(name="form_element")
* @ORM\HasLifecycleCallbacks
*/

class Element {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Fieldset",inversedBy="element")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $fieldset;
	
	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $name;
	
	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $label;
	
	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $class;
	
	/**
     * @ORM\Column(type="text",  nullable=true)
     */
	protected $attributes;	
	
	/**
     * @ORM\Column(type="text",  nullable=true)
     */
	protected $options;	
	
	/**
	 * @ORM\OneToMany(targetEntity="Data", mappedBy="element", cascade={"persist"})
	 */
	protected $data;
	
	/**
	 * @ORM\OneToMany(targetEntity="ElementAttribute", mappedBy="element", cascade={"persist"})
	 */
	protected $elementattributes;
	
	/**
     * @ORM\Column(type="integer",  nullable=true)
     */
	protected $sort = 0;
	
	public function __construct(){
		$this->elementattributes = new ArrayCollection();
	}

    public function getId(){
		return $this->id;
	}
	
	public function getSort(){
		return $this->sort;
	}
	
	public function setSort($i){
		$this->sort = $i;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($i){
		$this->name = $i;
	}
	
	public function getLabel(){
		return $this->label;
	}
	
	public function setLabel($i){
		$this->label = $i;
	}
	
	public function getClass(){
		return $this->class;
	}
	
	public function setClass($i){
		$this->class = $i;
	}
	
	public function getForm()
	{
		return $this->form;
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	public function setOptions($i){
		$this->options = $i;
	}
	
	public function getFieldset()
	{
		return $this->fieldset;
	}
	
	public function setFieldset($i)
	{
		$this->fieldset = $i;
	}

	public function getData()
	{
		return $this->data;
	}
	
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	public function setAttributes($i)
	{
		$this->attributes = $i;
	}
	
	public function getAttributesForForm(){
		$data = array();
		foreach(explode("\r\n",$this->attributes) as $line){
			$x = preg_split("/\|+/", $line);
			if(count($x) === 2) $data[$x[0]] = $x[1];
			else $data[$line] = $line;
		}
		return $data;
	}

	public function addElementAttributes(Collection $ii)
    {
        foreach ($ii as $i) {
			$i->setElement($this);
            $this->elementattributes->add($i);
        }
    }
	
    public function removeElementAttributes(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setElement(null);
            $this->elementattributes->removeFieldset($i);
        }
    }


}