<?php

namespace Bacon\Bundle\MenuBundle\Twig\Extension;

use Knp\Menu\Twig\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Twig_Extension;
use \AppKernel;

class MenuExtension extends Twig_Extension
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @param Helper $helper
     * @param AppKernel $container
     */
    public function __construct(Helper $helper, AppKernel $kernel,$className,$methodName)
    {
        $this->helper       = $helper;
        $this->kernel       = $kernel;
        $this->className    = $className;
        $this->methodName   = $methodName;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bacon_menu_full_render',array($this,'renderFull'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bacon_menu';
    }

    public function renderFull()
    {

        $bundles = $this->kernel->getBundles();

        $return  = '';

        foreach ($bundles as $bundle) {

            $bundlePath         =   $bundle->getPath();
            $bundleNamespace    =   $bundle->getNamespace();
            $bundleAlias        =   $bundle->getName();

            if (file_exists($bundlePath . '/Menu/Builder.php')) {
                $classFullName = $bundleNamespace . '\\' . $this->className;

                if (method_exists($classFullName,$this->methodName)) {
                    $return .= $this->helper->render($bundleAlias .':Builder:'. $this->methodName,array(
                        'firstClass' => 'teste_primeira_classe',
                        'currentClass'  => 'active'
                    ));
                }
            }
        }
        
        return $return;
    }
}
