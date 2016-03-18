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
            'name' => 'Thành viên',
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-users"></i>',
            'sub-menu' => [
                'Create' => [
                    'name' => 'Thêm',
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'name' => 'Tất cả',
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ],
                'Contact' => [
                    'name' => 'Khách hàng',
                    'controller' => 'admin',
                    'action' => 'showcontact',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Category' => [
            'name' => 'DM Bài viết',
            'controller' => '',
            'action' => '',
            'icon' => 'DB',
            'sub-menu' => [
                'Create' => [
                    'name' => 'Thêm',
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'name' => 'Tất cả',
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Article' => [
            'name' => 'Bài viết',
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-file-pdf-o"></i>',
            'sub-menu' => [
                'Create' => [
                    'name' => 'Thêm',
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'name' => 'Tất cả',
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Pcategory' => [
            'name' => 'DM Sản phẩm',
            'controller' => '',
            'action' => '',
            'icon' => 'DS',
            'sub-menu' => [
                'Create' => [
                    'name' => 'Thêm',
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'name' => 'Tất cả',
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Product' => [
            'name' => 'Sản phẩm',
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-cube"></i>',
            'sub-menu' => [
                'Create' => [
                    'name' => 'Thêm',
                    'controller' => 'admin',
                    'action' => 'create',
                    'icon' => '<i class="fa fa-plus"></i>',
                ],
                'Listing' => [
                    'name' => 'Tất cả',
                    'controller' => 'admin',
                    'action' => '',
                    'icon' => '<i class="fa fa-bars"></i>',
                ]
            ]
        ],
        'Slug' => [
            'name' => 'SEO đường dẫn',
            'controller' => '',
            'action' => '',
            'icon' => '<i class="fa fa-users"></i>',
            'sub-menu' => [
                'Listing' => [
                    'name' => 'Tất cả',
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
        $controllerName = strtolower($this->view->getControllerName());
        $actionName = $this->view->getActionName() == 'index' ? '' : $this->view->getActionName();

        foreach ($this->_leftbar as $caption => $option) {
            if ($option['controller'] == '' && $option['action'] == '' && $this->dispatcher->getModuleName() != strtolower($caption)) {
                echo '<li class="">';
            } else {
                echo '<li class="open">';
            }

            if ($option['controller'] !== '' && $option['action'] !== '') {
                echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $option['name']);
            } else {
                echo '<a href="javascript:;">';
                echo '<span class="title">' . $option['name'] . '</span>';
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

                    echo $this->tag->linkTo($sub_option['controller'] . '/' . strtolower($caption) . '/' . $sub_option['action'], $sub_option['name']);
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
