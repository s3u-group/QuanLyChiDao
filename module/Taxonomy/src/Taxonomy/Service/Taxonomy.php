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
}