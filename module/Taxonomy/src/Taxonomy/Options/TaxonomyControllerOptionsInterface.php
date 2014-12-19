<?php
namespace Taxonomy\Options;

interface TaxonomyControllerOptionsInterface
{
    /**
     * set taxonomies
     *
     * @param array $taxonomies
     * @return ModuleOptions
     */
    public function setTaxonomies($taxonomies);

    /**
     * get taxonomies
     *
     * @return array
     */
    public function getTaxonomies();
}
