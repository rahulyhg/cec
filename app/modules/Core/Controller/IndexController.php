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
 *
 * @RoutePrefix("/admin", name="admin-dashboard-home")
 */
class IndexController extends AbstractAdminController
{
    /**
     * Core not found page.
     *
     * @return void
     *
     */
    public function indexAction()
    {
        $this->response->setStatusCode('404', 'Page not found');
    }

    /**
     * Admin dashboard.
     *
     * @return void
     *
     * @Get("/dashboard", name="admin-dashboard-index")
     */
    public function dashboardAction()
    {
        $this->bc->add($this->lang->_('title-index'), 'admin/dashboard');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
        ]);
    }
}
