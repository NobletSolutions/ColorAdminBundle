<?php

namespace NS\ColorAdminBundle\Twig;


/**
 * Description of BundleExistence
 *
 * @author gnat
 */
class BundleExistence extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $bundles;

    /**
     *
     * @param array $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bundleExists', array($this, 'bundleExists')),
        );
    }

    /**
     *
     * @param string $bundle
     * @return boolean
     */
    public function bundleExists($bundle)
    {
        return array_key_exists($bundle, $this->bundles);
    }
}
