<?php
namespace Form\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\ServiceManager\ServiceManager;

use Zend\Form\Annotation;

/** 
* @ORM\Entity 
* @ORM\Table(name="form_uploads")
* @ORM\HasLifecycleCallbacks
*/
class Upload {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $name = null;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $type = null;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $tmp_name = null;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $error = null;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $size = null;
	
	/**
     * @ORM\ManyToOne(targetEntity="ParentData",inversedBy="uploads")
	 * @ORM\JoinColumn(name="form_parent_data_id", referencedColumnName="id", onDelete="CASCADE")
     */
	protected $parentdata;
	
	public function __construct($service)
    {
        $this->service = $service;
    }
	
	/**
	* @ORM\PrePersist
	**/
	public function onPrePersist()
	{	
		if(!$this->name) return;
		$dir = "./data/uploads/form/";
		if(!file_exists($dir.'thumbnail')){
			if(!mkdir($dir.'thumbnail', 0755, true))
				throw new \Exception("Failed to create upload folders");
		}
		
		$fileInfo = pathinfo($this->name);
		$oldName = $this->name;
		$newFileName = $this->generateCode(16).'.'.$fileInfo['extension'];
		$this->name = $newFileName;
		rename('./data/uploads/tmp/'.$oldName, $dir.$newFileName);
		
		$thumbnailer = $this->service->get('WebinoImageThumb');
		
		$thumb = $thumbnailer->create( $dir . $newFileName , $options = array('jpegQuality' => 90));				
		$fileInfo = pathinfo( $newFileName );				
		
		// Save Thumbnails
		$thumb->resize(120, 96);
		$thumb->save($dir .'thumbnail/'.$newFileName);
	}
	
	public function getThumbnailPath(){
		return '/form-uploads/thumbnail/'.$this->name;
	}
	
	public function generateCode($length = 6){
		if ($length <= 0)
			return false;
		$code = "";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		srand((double)microtime() * 1000000);
		for ($i = 0; $i < $length; $i++)
			$code = $code . substr($chars, rand() % strlen($chars), 1);
		return $code;
		
	}
	
	public function getId(){
		return $this->id;	
	}
	
	public function setId($i){
		$this->id = $id;
	}
	
	public function getPath()
	{
		return '/form-uploads/'.$this->name;
	}
	
	public function getDownloadPath(){
		return './data/uploads/form/'.$this->name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($i){
		$this->name = $i;
	}

    public function getType(){
		return $this->type;
	}
		
	public function setType($i){
		$this->type = $i;
	}
	
	public function getTmpName(){
		return $this->tmp_name;
	}
	
	public function setTmpName($i){
		$this->tmp_name = $i;
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setError($i){
		$this->error = $i;
	}
	
	public function getSize(){
		return $this->size;
	}
	
	public function setSize($i){
		$this->size = $i;
	}
	
	public function getParentData(){
		return $this->parentdata;
	}
	
	public function setParentData($i){
		$this->parentdata = $i;
	}

}