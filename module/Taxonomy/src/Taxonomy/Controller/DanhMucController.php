<?php namespace Taxonomy\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\ServiceManager\ServiceManager;

 use Taxonomy\Entity\Term;
 use Taxonomy\Entity\TermTaxonomy;
 use Taxonomy\Form\AddTaxonomyForm;
 use Taxonomy\Form\UpdateTaxonomyForm;
 use Taxonomy\Options\TaxonomyControllerOptionsInterface;

 class DanhMucController extends AbstractActionController
 {
 	protected $entityManager;

    /**
     * @var TaxonomyControllerOptionsInterface
     */
    protected $options;

    protected $taxonomyService;

    public function getEntityManager()
    {
        if(!$this->entityManager)
        {
          $this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    public function getTaxonomyService(){
      if(!$this->taxonomyService)
        {
          $this->taxonomyService=$this->getServiceLocator()->get('taxonomyService');
        }
        return $this->taxonomyService;
    }

    public function loaiCongViecAction(){
      $entityManager = $this->getEntityManager();
      $service = $this->getTaxonomyService();

      $form = new AddTaxonomyForm($entityManager);
      $loai = new TermTaxonomy();
      $loai->setTaxonomy('loai-cong-viec');
      $form->bind($loai);

      $request = $this->getRequest();
      if ($request->isPost()) {
          $form->setData($request->getPost());

          if ($form->isValid()) {
              $entityManager->persist($loai);
              $entityManager->flush();
              $this->flashMessenger()->addSuccessMessage('Đã thêm!');
              return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'loai-cong-viec'));
          }
      }
      $loais = $service->getTaxonomy('loai-cong-viec');

      return array(
        'loais' => $loais,
        'form' => $form
      );
    }

    public function suaLoaiCongViecAction(){
      $id = (int) $this->params()->fromRoute('id', 0);
      if(!$id) return $this->redirect()->toRoute('danh_muc/crud', array('action'=>'loai-cong-viec'));
      $entityManager = $this->getEntityManager();
      $loai = $entityManager->getRepository('Taxonomy\Entity\TermTaxonomy')->find($id);
      $form = new UpdateTaxonomyForm($entityManager);
      $form->bind($loai);

      $request = $this->getRequest();
      if ($request->isPost()) {
          $form->setData($request->getPost());

          if ($form->isValid()) {
              $entityManager->flush();
              $this->flashMessenger()->addSuccessMessage('Đã sửa!');
              return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'loai-cong-viec'));
          }
      }
      return array(
        'id' => $id,
        'form' => $form
      );
    }

    public function xoaLoaiCongViecAction(){
      $id = (int) $this->params()->fromRoute('id', 0);
      if(!$id) return $this->redirect()->toRoute('danh_muc/crud', array('action'=>'loai-cong-viec'));
      $entityManager = $this->getEntityManager();
      $loai = $entityManager->getRepository('Taxonomy\Entity\TermTaxonomy')->find($id);

      if($this->daSuDungLoai($loai)){
        $this->flashMessenger()->addErrorMessage('Danh mục này đang sử dụng, không được xóa!');
      }
      else{
        $entityManager->remove($loai);
        $entityManager->flush();
        $this->flashMessenger()->addSuccessMessage('Đã xóa!');
      }

      return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'loai-cong-viec'));
    }

    public function daSuDungLoai($loai){
      $entityManager = $this->getEntityManager();
      $query = $entityManager->createQuery('select count(c.id) from CongViec\Entity\CongViec c where c.loai = ?1');
      $query->setParameter(1, $loai->getId());
      if($query->getSingleScalarResult() != 0)
        return 1;
      return 0;
    }

    public function linhVucAction(){
      $entityManager = $this->getEntityManager();
      $service = $this->getTaxonomyService();

      $form = new AddTaxonomyForm($entityManager);
      $loai = new TermTaxonomy();
      $loai->setTaxonomy('linh-vuc');
      $form->bind($loai);

      $request = $this->getRequest();
      if ($request->isPost()) {
          $form->setData($request->getPost());

          if ($form->isValid()) {
              $entityManager->persist($loai);
              $entityManager->flush();

              return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'linh-vuc'));
          }
      }
      $loais = $service->getTaxonomy('linh-vuc');

      return array(
        'loais' => $loais,
        'form' => $form
      );
    }

    public function suaLinhVucAction(){
      $id = (int) $this->params()->fromRoute('id', 0);
      if(!$id) return $this->redirect()->toRoute('danh_muc/crud', array('action'=>'linh-vuc'));
      $entityManager = $this->getEntityManager();
      $loai = $entityManager->getRepository('Taxonomy\Entity\TermTaxonomy')->find($id);
      $form = new UpdateTaxonomyForm($entityManager);
      $form->bind($loai);

      $request = $this->getRequest();
      if ($request->isPost()) {
          $form->setData($request->getPost());

          if ($form->isValid()) {
              $entityManager->flush();

              return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'linh-vuc'));
          }
      }
      return array(
        'id' => $id,
        'form' => $form
      );
    }

    public function xoaLinhVucAction(){
      $id = (int) $this->params()->fromRoute('id', 0);
      if(!$id) return $this->redirect()->toRoute('danh_muc/crud', array('action'=>'linh-vuc'));
      $entityManager = $this->getEntityManager();
      $loai = $entityManager->getRepository('Taxonomy\Entity\TermTaxonomy')->find($id);
      
      if($this->daSuDungLinhVuc($loai)){
        $this->flashMessenger()->addErrorMessage('Danh mục này đang sử dụng, không được xóa!');
      }
      else{
        $entityManager->remove($loai);
        $entityManager->flush();
        $this->flashMessenger()->addSuccessMessage('Đã xóa!');
      }
      
      return $this->redirect()->toRoute('danh_muc/crud', array('action' => 'linh-vuc'));
    }

    public function daSuDungLinhVuc($loai){
      $entityManager = $this->getEntityManager();
      $query = $entityManager->createQuery('select count(c.id) from CongViec\Entity\CongViec c where c.linhVuc = ?1');
      $query->setParameter(1, $loai->getId());
      if($query->getSingleScalarResult() != 0)
        return 1;
      return 0;
    }
}