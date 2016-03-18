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
     * @Get("/dashboard", name="core-admin-dashboard")
     */
    public function dashboardAction()
    {
        // $content = $this->filesystem->read('app/modules/Core/View/Layout/Default/site-footer.volt');

        $this->bc->add($this->lang->_('title-dashboard'), 'admin/dashboard');
        $this->view->setVars([
            'bc' => $this->bc->generate()
        ]);
    }

    /**
     * Edit file system.
     *
     * @return void
     *
     * @Get("/editfile/{file:[a-zA-Z0-9]+}", name="core-admin-editfile")
     */
    public function editfileAction($file = "")
    {
        switch ($file) {
            case 'header':
                $file = 'app/modules/Core/View/Layout/Default/site-header.volt';
                break;
            case 'footer':
                $file = 'app/modules/Core/View/Layout/Default/site-footer.volt';
                break;
            case 'mfooter':
                $file = 'app/modules/Core/View/Layout/Default/m.site-footer.volt';
                break;


        }

        $content = $this->filesystem->read($file);

        $this->bc->add($this->lang->_('title-dashboard'), 'admin/dashboard');
        $this->bc->add($this->lang->_('title-editfile') . ':' . $file, '');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'editContent' => $content,
            'filepath' => $file
        ]);
    }

    /**
     * Save file system.
     *
     * @return void
     *
     * @Post("/saveeditfile", name="core-admin-saveeditfile")
     */
    public function saveeditfileAction()
    {
        $result = $meta = [];
        $filepath = base64_decode($this->request->getPost('file'));
        $content = base64_decode(str_replace(" ", "+", $this->request->getPost('content')));

        $res = $this->filesystem->put($filepath, $content);

        if ($res) {
            $meta['status'] = true;
            $meta['message'] = 'File updated!';
        } else {
            $meta['success'] = false;
            $meta['message'] = 'Error occurred when update file!!!';
        }

        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }
}
