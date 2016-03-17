<?php
namespace Category\Controller;

use Core\Controller\AbstractAdminController;
use Category\Model\Category as Category;
use Core\Model\Slug as SlugModel;
use Core\Helper\Utilities;

/**
 * Category Home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/category", name="admin-category-home")
 */
class AdminController extends AbstractAdminController
{
    /**
     * Main action.
     *
     * @return void
     *
     * @Get("/", name="admin-category-index")
     */
    public function indexAction()
    {
        $myCategories = Category::find(array('order' => 'lft'));

        $this->bc->add($this->lang->_('title-index'), 'admin/category');
        $this->bc->add($this->lang->_('title-listing'), 'admin/category');
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
     * @Route("/create", methods={"GET", "POST"}, name="admin-category-create")
     */
    public function createAction()
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                if ($formData['root'] > 1) {
                    $root = Category::findFirst($formData['root']);
                } else {
                    $root = self::checkRoot();
                }

                // Create category
                $myCategory = new Category();
                $myCategory->assign([
                    'name' => $formData['name'],
                    'status' => $formData['status'],
                    'root' => $formData['root'],
                    'seodescription' => $formData['seodescription'],
                    'seokeyword' => $formData['seokeyword']
                ]);

                if ($myCategory->appendTo($root)) {
                    // insert to slug table
                    $mySlug = new SlugModel();
                    $mySlug->assign([
                        'uid' => $this->session->get('me')->id,
                        'slug' => Utilities::slug($formData['name']),
                        'hash' => md5(Utilities::slug($formData['name'])),
                        'objectid' => $myCategory->id,
                        'model' => SlugModel::MODEL_CATEGORY,
                        'status' => SlugModel::STATUS_ENABLE
                    ]);
                    if (!$mySlug->create()) {
                        foreach ($mySlug->getMessages() as $msg) {
                            $this->flash->error($msg);
                        }
                    }

                    $formData = [];
                    $this->flash->success(str_replace('###name###', $myCategory->name, $this->lang->_('message-create-category-success')));
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

        $this->bc->add($this->lang->_('title-index'), 'admin/category');
        $this->bc->add($this->lang->_('title-create'), 'admin/category/create');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => Category::getStatusList(),
            'categories' => Category::find(['order' => 'lft'])
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-category-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            $formData = array_merge($formData, $this->request->getPost());

            // Find category with primaryKey ID
            $myCategory = Category::findFirst([
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
                $this->flash->success(str_replace('###name###', $myCategory->name, $this->lang->_('message-update-category-success')));
            } else {
                foreach ($myCategory->getMessages() as $msg) {
                    $message .= $this->lang->_($msg->getMessage()) . '<br />';
                }
                $this->flash->error($message);
            }
        }

        $formData = Category::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ])->toArray();

        $this->bc->add($this->lang->_('title-index'), 'admin/category');
        $this->bc->add($this->lang->_('title-edit'), 'admin/category/edit');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => Category::getStatusList()
        ]);
    }

    /**
     * Delete action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-category-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myCategory = Category::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]]);

        // delete slug
        SlugModel::findFirst([
            'objectid = :id: AND model = "Category"',
            'bind' => ['id' => $myCategory->id]
        ])->delete();

        if ($myCategory->delete()) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myCategory->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/category');
    }

    /**
     * Sortable action.
     *
     * @return void
     *
     * @Post("/sortable", name="admin-category-sortable")
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
                $parentCategory = Category::findFirst($root->id);
                // echo 'Parent is ' . $root->id . ', ';
                self::doSort($child);
                // print_r($child);
                $childCategory = Category::findFirst($child->id);
                $childCategory->moveAsLast($parentCategory);
            }
            // echo "---------\n";
        }
    }

    private static function checkRoot()
    {
        $root = Category::findFirst('lft = 1');

        if (!$root) {
            $root = new Category();
            $root->root = 1;
            $root->status = Category::STATUS_ENABLE;
            $root->name = 'Root';
            $root->description = 'Root description';
            $root->saveNode();
        }

        return $root;
    }
}
