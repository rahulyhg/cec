<?php
namespace Category\Controller;

use Core\Controller\AbstractAdminController;
use Category\Model\Category as Category;
use Category\Model\CategoryLang as CategoryLang;
use Core\Model\Language as Language;

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
            $formData = array_merge($formData, $this->request->getPost());

            if ($formData['root'] > 1) {
                $root = Category::findFirst($formData['root']);
            } else {
                $root = self::checkRoot();
            }

            // Create category
            $myCategory = new Category();
            $myCategory->assign([
                'iconpath' => $formData['iconpath'],
                'status' => $formData['status'],
                'root' => $formData['root']
            ]);

            // Create category languages
            $langPacks = [];
            // Create default language record when chosen language != default language.
            if ($formData['lcode'] != $this->config->global->defaultLanguage) {
                $langPacks[0] = new CategoryLang();
                $langPacks[0]->name = 'n/a';
                $langPacks[0]->lcode = $this->config->global->defaultLanguage;
            }

            // Create selected language
            $langPacks[1] = new CategoryLang();
            $langPacks[1]->assign([
                'lcode' => $formData['lcode'],
                'name' => $formData['name'],
                'description' => $formData['description'],
                'seokeyword' => $formData['seokeyword'],
                'seodescription' => $formData['seodescription'],
            ]);

            // Add related language to category
            $myCategory->lang = $langPacks;

            if ($myCategory->appendTo($root)) {
                $formData = [];
                $this->flash->success(str_replace('###name###', $myCategory->getOneLangBySession()->name, $this->lang->_('message-create-category-success')));
            } else {
                foreach ($myCategory->getMessages() as $msg) {
                    $message .= $this->lang->_($msg->getMessage()) . '<br />';
                }
                $this->flash->error($message);
            }
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/category');
        $this->bc->add($this->lang->_('title-create'), 'admin/category/create');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => Category::getStatusList(),
            'categories' => Category::find(['order' => 'lft']),
            'languages' => Language::find()
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
                'iconpath' => $formData['iconpath'],
                'status' => $formData['status']
            ]);

            // Update Category Model and Related Category Model Language
            if ($myCategory->update()) {
                foreach ($formData['lang'] as $langCode => $langData) {
                    // Using async function will be not return any exception error
                    // on model validation, it keep data not change when error occured.
                    $myCategory->getLangs()->update($langData, function($catLang) use ($langCode) {
                        if ($catLang->lcode == $langCode) {
                            return true;
                        }

                        return false;
                    });
                }

                $this->flash->success(str_replace('###name###', $myCategory->getOneLangBySession()->name, $this->lang->_('message-update-category-success')));
            } else {
                foreach ($myCategory->getMessages() as $msg) {
                    $message .= $this->lang->_($msg->getMessage()) . '<br />';
                }
                $this->flash->error($message);
            }
        }

        $myCategory = Category::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myCategory->toArray();
        $formData['lang'] = $myCategory->getLangs()->toArray();
        $formData['thumbnailImage'] = $myCategory->getThumbnailImage();

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

        if ($myCategory->delete() && $myCategory->getLangs()->delete()) {
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
     * Add additional language action.
     *
     * @return void
     *
     * @Route("/addlanguage/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-category-addlanguage")
     */
    public function addlanguageAction($id = 0)
    {
        $this->bc->add($this->lang->_('title-index'), 'admin/category');
        $this->bc->add($this->lang->_('title-edit'), 'admin/category/edit');
        $this->view->setVars([
            'bc' => $this->bc->generate(),
            'formData' => $formData,
            'statusList' => Category::getStatusList()
        ]);
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

    /**
     * Upload iconpath action.
     *
     * @return void
     *
     * @Post("/uploadiconpath", name="admin-category-uploadiconpath")
     */
    public function uploadiconpathAction()
    {
        $meta = $result = [];
        $myCategory = new Category();
        $upload = $myCategory->processUpload();

        if ($upload == $myCategory->isSuccessUpload()) {
            $meta['status'] = true;
            $meta['message'] = $this->lang->_('message-icon-path-upload-success');
            $result = $myCategory->getInfo();
        } else {
            $meta['success'] = false;
            $meta['message'] = $myCategory->getMessage();
        }

        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
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

            $rootLang = new CategoryLang();
            $rootLang->lcode = $this->lang->_('default-root-code');
            $rootLang->name = $this->lang->_('default-root-name');
            $rootLang->description = $this->lang->_('default-root-description');
            $root->lang = $rootLang;

            $root->saveNode();
        }

        return $root;
    }
}
