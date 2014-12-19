<?php
namespace Taxonomy\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
 
class TaxonomyPlugin extends AbstractPlugin{

    protected $_om;

    protected $level = 0;

    protected $result = array();

    /**
     * reorder datas to parent-child array with the level attr
     *
     * @param array $tree
     * @param Taxonomy\Entity\TermTaxonomy $root
     * @return array
     */
    public function parseTree($tree, $root = null) {
        foreach($tree as $i=>$child) {
            $parent = $child->getParent();
            if($parent == $root) {
                unset($tree[$i]);
                $child->setLevel($this->level);
                $this->result[] = $child;
                $this->level++; //tang cap
                $this->parseTree($tree, $child);
                $this->level--; //giam cap
            }
        }
        return $this->result;    
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
        $objectManager = $this->getObjectManager();
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

    /**
     * check whether a taxonomy inside the list taxonomies in config array
     *
     * @param array $taxonomies
     * @param string $taxonomy
     * @return bool
     */
    public function checkInside($taxonomies, $taxonomy){
        foreach($taxonomies as $tax)
            if($tax['slug'] == $taxonomy)
                return true;
        return false;
    }

    public function setObjectManager($om){
    	$this->_om = $om;
    }

    public function getObjectManager(){
    	return $this->_om;
    }

}