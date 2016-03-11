<?php
namespace Core\Controller;

/**
 * Error handler.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class IndexController extends AbstractController
{
    /**
     * 404 page.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->response->setStatusCode('404', 'Page not found');
    }
}
