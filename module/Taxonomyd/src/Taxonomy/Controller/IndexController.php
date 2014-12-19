<?php namespace Taxonomy\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\ServiceManager\ServiceManager;

 use Taxonomy\Entity\Term;
 use Taxonomy\Entity\TermTaxonomy;
 use Taxonomy\Form\AddTaxonomyForm;
 use Taxonomy\Options\TaxonomyControllerOptionsInterface;

 class IndexController extends AbstractActionController
 {
 	protected $entityManager;

    /**
     * @var TaxonomyControllerOptionsInterface
     */
    protected $options;

    public function getEntityManager()
    {
        if(!$this->entityManager)
        {
          $this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    public function adminAction(){
    	$taxonomies = $this->getOptions()->getTaxonomies();
        return array(
            'taxonomies' => $taxonomies
        );
    }

 	public function indexAction()
 	{
        $tax = $this->params()->fromRoute('tax', null);
        if (!$tax) {
            return $this->redirect()->toRoute('taxonomies');
        }
        $this->checkIfInsideList($tax);

 		$objectManager = $this->getEntityManager();

        $query = $objectManager->createQuery('select t1,t2 from Taxonomy\Entity\TermTaxonomy t1 join t1.term t2 where t1.taxonomy = :tax');
        $query->setParameter('tax',$tax);
        $taxons = $query->getResult();

        $taxonomyPlugin = $this->taxonomyPlugin();
        $taxons = $taxonomyPlugin->parseTree($taxons);

 		return array(
 			'taxons' => $taxons,
            'tax' => $tax
 		);
 	}

 	public function addAction()
 	{
 		$tax = $this->params()->fromRoute('tax', null);
        if (!$tax) {
            return $this->redirect()->toRoute('taxonomies');
        }
        $this->checkIfInsideList($tax);
 		
 		$objectManager = $this->getEntityManager();
        $form = new AddTaxonomyForm($objectManager);
        $taxonomy = new TermTaxonomy();
        $taxonomy->setTaxonomy($tax);
        $form->bind($taxonomy);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager->persist($taxonomy);
                $objectManager->flush();

                return $this->redirect()->toRoute('taxonomies/taxonomy', array('tax'=>$tax));
            }
        }
                
        return array(
        	'form' => $form,
        	'tax' => $tax,
            'valueOptions' => $this->taxonomyPlugin()->getValueForOption($tax)
        ); 
 	}

 	public function editAction()
 	{
 		die('Sua taxon ' . $this->params()->fromRoute('tax') . ' co id la '.$this->params()->fromRoute('id', 0));
 	}

 	public function deleteAction()
 	{
 	}

    public function notFoundAction(){
        $tax = $this->params()->fromQuery('tax');
        return array(
            'tax' => $tax
        );
    }

    public function setOptions(TaxonomyControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof TaxonomyControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('taxonomy_module_options'));
        }
        return $this->options;
    }

    public function checkIfInsideList($tax){
        $taxonomies = $this->getOptions()->getTaxonomies();
        if(!$this->taxonomyPlugin()->checkInside($taxonomies, $tax))
            return $this->redirect()->toRoute('taxonomies/not_found', array(), array('query'=>array('tax'=>$tax)));
        // Query route deprecated as of ZF 2.1.4; use the "query" option of the HTTP router\'s assembling method instead
    }
 }
?>