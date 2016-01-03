<?php

namespace Bacon\Bundle\MenuBundle\Twig\Extension;

use Knp\Menu\Twig\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Twig_Extension;

class MenuExtension extends Twig_Extension
{

    private $helper;
    private $container;

    /**
     * @param Helper $helper
     * @param ContainerInterface $container
     */
    public function __construct(Helper $helper, ContainerInterface $container)
    {
        $this->helper = $helper;
        $this->container = $container;
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
        $kernel  = $this->container->get('kernel');
        $bundles = $kernel->getBundles();
        $return  = '';

        foreach ($bundles as $bundle) {

            $bundlePath         =   $bundle->getPath();
            $bundleNamespace    =   $bundle->getNamespace();
            $bundleAlias        =   $bundle->getName();

            if (file_exists($bundlePath . '/Menu/Builder.php')) {
                $classFullName = $bundleNamespace . '\\' . $this->container->getParameter('bacon.name_class.menu');

                if (method_exists($classFullName,$this->container->getParameter('bacon.name_method.menu'))) {
                    $return .= $this->helper->render($bundleAlias .':Builder:'. $this->container->getParameter('bacon.name_method.menu'),array(
                        'firstClass' => 'teste_primeira_classe',
                        'currentClass'  => 'active'
                    ));
                }
            }
        }
        
        return $return;
    }
}
