<?php
namespace Core\Plugin;

use Phalcon\Mvc\User\Component;

/**
 * Helps to build UI elements for the application
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class AdminElements extends Component
{
    private $_leftbar = [
        'User' => [
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-users"></i>',
            'sub-menu' => [
                'Create' => [
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Category' => [
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-adn"></i>',
            'sub-menu' => [
                'Create' => [
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Pcategory' => [
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-adn"></i>',
            'sub-menu' => [
                'Create' => [
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Article' => [
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-adn"></i>',
            'sub-menu' => [
                'Create' => [
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Product' => [
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-adn"></i>',
            'sub-menu' => [
                'Create' => [
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
    ];

    /**
     * Returns left sidebar
     */
    public function getSidebar()
    {
        $lang = $this->di->get('lang');
        $controllerName = strtolower($this->view->getControllerName());
        $actionName = $this->view->getActionName() == 'index' ? '' : $this->view->getActionName();

        foreach ($this->_leftbar as $caption => $option) {
            if ($option['controller'] == '' && $option['action'] == '' && $this->dispatcher->getModuleName() != strtolower($caption)) {
                echo '<li class="">';
            } else {
                echo '<li class="open">';
            }

            if ($option['controller'] !== '' && $option['action'] !== '') {
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption);
            } else {
                echo '<a href="javascript:;">';
                echo '<span class="title">' . $caption . '</span>';
                echo '<span class="arrow '. ($this->dispatcher->getModuleName() == strtolower($caption)? 'open' : '') .'"></span>';
                echo '</a>';

                if ($option['icon'] !== '') {
                    echo '<span class="icon-thumbnail">' . $option['icon'] . '</span>';
                }
            }
            if (isset($option['sub-menu']) && !empty($option['sub-menu'])) {
                echo '<ul class="sub-menu" style="display:'. ($this->dispatcher->getModuleName() == strtolower($caption)? 'block' : 'none') .'">';
                foreach ($option['sub-menu'] as $sub_caption => $sub_option) {
                    if ($sub_option['controller'] == '' && $sub_option['action'] == '') {
                        echo '<li class="">';
                    } else {
                        if ($sub_option['controller'] == $controllerName && ($sub_option['action'] == $actionName) && $this->dispatcher->getModuleName() == strtolower($caption)) {
                            echo '<li class="active">';
                        } else {
                            echo '<li class="">';
                        }
                    }

                    echo $this->tag->linkTo($sub_option['controller'] . '/' . strtolower($caption) . '/' . $sub_option['action'], $sub_caption);
                    if ($sub_option['icon'] !== '') {
                        echo '<span class="icon-thumbnail">' . $sub_option['icon'] . '</span>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}
