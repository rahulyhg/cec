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
class ErrorController extends AbstractController
{
    /**
     * 404 page.
     *
     * @return void
     */
    public function show404Action()
    {
        $this->response->setStatusCode('404', 'Page not found');
    }

    /**
     * 500 page.
     *
     * @return void
     */
    public function show500Action()
    {
        $this->response->setStatusCode('500', 'Internal Server Error');
    }
}
