BaconMenuBundle
===============

Este bundle é responsavel por Customizar a criação de menus do KnpMenuBundle

## Instalação

Para instalar o bundle basta rodar o seguinte comando abaixo:

```bash
$ composer require baconmanager/menu-bundle
```
Agora adicione os seguintes bundles no arquivo AppKernel.php:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    // ...
    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Bacon\Bundle\MenuBundle\BaconMenuBundle(),
    // ...
}
```
Para criar um novo menu basta criar uma classe no namespace MyBundle/Menu/Builder como no exemplo abaixo:

```php
<?php
// src/AppBundle/Menu/Builder.php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function addMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $translate = $this->container->get('translator');

        // Menu Catalog
        $menu->addChild($translate->trans('Category'))->setAttribute('icon', '<i class="fa fa-book"></i>');
        $menu[$translate->trans('Category')]->addChild($translate->trans('List'),array('route' => 'admin_category'));
        $menu[$translate->trans('Category')]->addChild($translate->trans('New'),array('route' => 'admin_category_new'));

        return $menu;
    }
}
```
Renderizando o menu no layout(twig):

```
{{ bacon_menu_full_render() }}
```