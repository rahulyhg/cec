<?php
namespace Pcategory\Controller;

use Core\Controller\AbstractAdminController;
use Pcategory\Model\Pcategory as ProductCategory;

/**
 * Product Category Home.
 *
 * @pcategory  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/pcategory", name="admin-pcategory-home")
 */
class AdminController extends AbstractAdminController
{
    /**
     * Main action.
     *
     * @return void
     *
     * @Get("/", name="admin-pcategory-index")
     */
    public function indexAction()
    {
        $myCategories = ProductCategory::find(array('order' => 'lft'));

        $this->bc->add($this->lang->_('title-index'), 'admin/pcategory');
        $this->bc->add($this->lang->_('title-listing'), 'admin/pcategory');
        $this->view->setVars([
            'myCategories' => $myCategories,
            'bc' => $this->bc->generate(),
        ]);
    }

    /**
     * Create action.
     *
     * @return void
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-pcategory-create")
     */
    public function createAction()
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                if ($formData['root'] > 1) {
                    $root = ProductCategory::findFirst($formData['root']);
                } else {
                    $root = self::checkRoot();
                }

                // Create pcategory
                $myCategory = new ProductCategory();
                $myCategory->assign([
                    'name' => $formData['name'],
                    'status' => $formData['status'],
                    'root' => $formData['root'],
                    'seodescription' => $formData['seodescription'],
                    'seokeyword' => $formData['seokeyword']
                ]);

                if ($myCategory->appendTo($root)) {
                    $formData = [];
                    $this->flash->success(str_replace('###name###', $myCategory->name, $this->lang->_('message-create-pcategory-success')));
                } else {
                    foreach ($myCategory->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/pcategory');
        $this->bc->add($this->lang->_('title-create'), 'admin/pcategory/create');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => ProductCategory::getStatusList(),
            'categories' => ProductCategory::find(['order' => 'lft'])
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-pcategory-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            $formData = array_merge($formData, $this->request->getPost());

            // Find pcategory with primaryKey ID
            $myCategory = ProductCategory::findFirst([
                'id = :id:',
                'bind' => ['id' => (int) $id]
            ]);
            $myCategory->assign([
                'name' => $formData['name'],
                'seodescription' => $formData['seodescription'],
                'seokeyword' => $formData['seokeyword'],
                'status' => $formData['status']
            ]);

            // Update Category Model
            if ($myCategory->update()) {
                $this->flash->success(str_replace('###name###', $myCategory->name, $this->lang->_('message-update-pcategory-success')));
            } else {
                foreach ($myCategory->getMessages() as $msg) {
                    $message .= $this->lang->_($msg->getMessage()) . '<br />';
                }
                $this->flash->error($message);
            }
        }

        $formData = ProductCategory::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ])->toArray();

        $this->bc->add($this->lang->_('title-index'), 'admin/pcategory');
        $this->bc->add($this->lang->_('title-edit'), 'admin/pcategory/edit');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => ProductCategory::getStatusList()
        ]);
    }

    /**
     * Delete action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-pcategory-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myCategory = ProductCategory::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]]);

        if ($myCategory->delete()) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myCategory->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/pcategory');
    }

    /**
     * Sortable action.
     *
     * @return void
     *
     * @Post("/sortable", name="admin-pcategory-sortable")
     */
    public function sortableAction()
    {
        $menuList = (array) $this->request->getJsonRawBody();
        foreach ($menuList as  $item) {
            self::doSort($item);
        }

        $this->view->setVars([
        ]);
    }

    private static function doSort($root)
    {
        if (isset($root->children) && count($root->children) > 0) {
            foreach ($root->children as $child) {
                $parentCategory = ProductCategory::findFirst($root->id);
                // echo 'Parent is ' . $root->id . ', ';
                self::doSort($child);
                // print_r($child);
                $childCategory = ProductCategory::findFirst($child->id);
                $childCategory->moveAsLast($parentCategory);
            }
            // echo "---------\n";
        }
    }

    private static function checkRoot()
    {
        $root = ProductCategory::findFirst('lft = 1');

        if (!$root) {
            $root = new ProductCategory();
            $root->root = 1;
            $root->status = ProductCategory::STATUS_ENABLE;
            $root->name = 'Root';
            $root->description = 'Root description';
            $root->saveNode();
        }

        return $root;
    }
}
