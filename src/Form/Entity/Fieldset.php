<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/**
* @ORM\Entity
* @ORM\Table(name="form_fieldset")
* @ORM\HasLifecycleCallbacks
*/

class Fieldset {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Form",inversedBy="fieldset")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $form;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $name;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $class = '\Zend\Form\Fieldset';

	/**
	 * @ORM\OneToMany(targetEntity="Element", mappedBy="fieldset", cascade={"persist"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	protected $element;

	/**
     * @ORM\Column(type="string",  nullable=true)
     */
	protected $label;

	/**
     * @ORM\Column(type="integer",  nullable=true)
     */
	protected $sort = 0;

	/**
     * @ORM\OneToMany(targetEntity="Fieldset", mappedBy="parent", orphanRemoval=true)
     */
    protected $children;

	/**
     * @ORM\ManyToOne(targetEntity="Fieldset", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    protected $description;

	public function __construct()
	{
		$this->element = new ArrayCollection();
		$this->children = new ArrayCollection();
	}

	public function getChildren(){
		return $this->children;
	}

	public function setChildren($i){
		$this->children = $i;
	}

	public function getParent(){
		return $this->parent;
	}

	public function setParent(Fieldset $i){
		$this->parent = $i;
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

	public function setForm($i)
	{
		$this->form = $i;
	}

	public function getElement(){
		return $this->element;
	}

	public function addElement(Collection $ii)
    {
        foreach ($ii as $i) {
			$i->setFieldset($this);
            $this->element->add($i);
        }
    }

    public function removeElement(Collection $ii)
    {
        foreach ($ii as $i) {
            $i->setFieldset(null);
            $this->element->removeElement($i);
        }
    }

	public function getSort(){
		return $this->sort;
	}

	public function setSort($i){
		$this->sort = $i;
	}

	public function generateFieldsetObject(){
		$fieldsetEntity = $this;
		$class = $fieldsetEntity->getClass();
		$fieldset = new $class($fieldsetEntity->getId());
		$fieldset->setLabel($fieldsetEntity->getLabel());
		foreach($fieldsetEntity->getElement() as $elementEntity){
			$elementClass = $elementEntity->getClass();
			$element = new $elementClass($elementEntity->getId());
			$element->setLabel($elementEntity->getLabel());
			if ($elementEntity->getOptions()) $element->setOptions(unserialize($elementEntity->getOptions()));
			$element->setAttributes($elementEntity->getAttributesForForm());

			if($fieldsetEntity)
			$fieldset->add($element);
		}
		return $fieldset;
	}

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
    }
}