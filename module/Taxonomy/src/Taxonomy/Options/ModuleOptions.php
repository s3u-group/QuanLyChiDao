<?php

namespace Taxonomy\Options;
use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements TaxonomyControllerOptionsInterface{
	/**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    protected $taxonomies = array();

    public function setTaxonomies($taxonomies){
    	$this->taxonomies = $taxonomies;
    	return $this;
    }

    public function getTaxonomies(){
    	return $this->taxonomies;
    }
}