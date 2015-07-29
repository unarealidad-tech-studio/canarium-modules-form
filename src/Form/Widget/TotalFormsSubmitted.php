<?php 

namespace Form\Widget;
use Settings\Widget\CanariumWidgetInterface;
use Zend\View\Model\ViewModel;

class TotalFormsSubmitted implements CanariumWidgetInterface
{
	protected $templatePath = 'form/admin/widget/total-forms-submitted';
	protected $serviceLocator;

	public function __construct(\Zend\ServiceManager\ServiceManager $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getView() 
	{	
		$view = new ViewModel(array( 'totalFormsSubmitted' => $this->getTotalFormsSubmitted() ));
		$view->setTemplate( $this->templatePath );
		return $view;
	}

	public function getTotalFormsSubmitted()
	{
		$em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
		$query = $em->createQuery('SELECT COUNT(d) FROM Form\Entity\ParentData d');
		$total = $query->getSingleScalarResult();
		return $total;
	}
}