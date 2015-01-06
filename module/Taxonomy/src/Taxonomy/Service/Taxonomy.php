<?php
namespace Taxonomy\Service;

class Taxonomy{

	protected $entityManager;

	public function setEntityManager($entityManager){
		$this->entityManager = $entityManager;
	}

	public function getEntityManager(){
		return $this->entityManager;
	}

	public function luu($termTaxonomy){
		$entityManager = $this->getEntityManager();
		$term = $termTaxonomy->getTerm();
		$query = $entityManager->createQuery('select t from Taxonomy\Entity\Term t where t.slug = :slug');
		$query->setParameter('slug', $term->getSlug());
		try {
			$term = $query->getSingleResult();
			$termTaxonomy->setTerm($term);
	    }
	    catch(\Doctrine\ORM\NoResultException $e) {
	    }
		$entityManager->persist($termTaxonomy);
        $entityManager->flush();
        return $termTaxonomy;
	}

	/**
     * fecth data from database and convert to array for checkbox format
     *
     * @param string $tax
     * @param int $id
     * @return array
     */
    public function getValueForOption($tax, $id = null){
        $options = array();
        $objectManager = $this->getEntityManager();
        if($id){
            /**
             * Khi co id tuc la dang sua, 
             * can loai bo taxon hien tai và cac con cua no
             */
            $query = $objectManager->createQuery('select t1,t2 from Taxonomy\Entity\TermTaxonomy t1 join t1.term t2 where t1.taxonomy = :tax and t1.id != :id');
            $query->setParameter('tax',$tax);
            $query->setParameter('id',$id);
            $taxons = $query->getResult();
            $taxons = $this->parseDel($taxons, $id);
        }
        else{
            $query = $objectManager->createQuery('select t1,t2 from Taxonomy\Entity\TermTaxonomy t1 join t1.term t2 where t1.taxonomy = :tax');
            $query->setParameter('tax',$tax);
            $taxons = $query->getResult();
        }
        

        foreach($taxons as $taxon){
            $options[$taxon->getId()] = $taxon->getTerm()->getName();
        }
        return $options;
    }

    /**
     * delete all child from a root
     * 
     * @param array $tree
     * @param int $root
     * @return array
     */
    public function parseDel($tree, $root = null) { //xoa cac con cua root
        foreach($tree as $i=>$child) {
            $parent = $child->getParent();
            if($parent)
                if($parent->getId() == $root) {
                    unset($tree[$i]);
                    $tree = $this->parseDel($tree, $child->getId());
                }
        }
        return $tree;    
    }
}