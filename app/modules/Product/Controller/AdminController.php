<?php
namespace Product\Controller;

use Core\Controller\AbstractAdminController;
use Product\Model\Product as ProductModel;
use Pcategory\Model\Pcategory as ProductCategory;
use Core\Model\Slug as SlugModel;
use Core\Helper\Utilities;

/**
 * Product admin home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/product", name="product-admin-home")
 */
class AdminController extends AbstractAdminController
{
    /**
     * number record on 1 page
     * @var integer
     */
    protected $recordPerPage = 30;

    /**
     * Main action.
     *
     * @return void
     *
     * @Route("/", methods={"GET", "POST"}, name="admin-product-index")
     */
    public function indexAction()
    {
        $currentUrl = $this->getCurrentUrl();
        $formData = $jsonData = [];

        if ($this->request->hasPost('fsubmitbulk')) {
            if ($this->security->checkToken()) {
                $bulkid = $this->request->getPost('fbulkid', null, []);

                if (empty($bulkid)) {
                    $this->flash->error($this->lang->_('default.no-bulk-selected'));
                } else {
                    $formData['fbulkid'] = $bulkid;

                    if ($this->request->getPost('fbulkaction') == 'delete') {
                        $deletearr = $bulkid;

                        // Start a transaction
                        $this->db->begin();
                        $successId = [];

                        foreach ($deletearr as $deleteid) {
                            $myProducts = ProductModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                            // If fail stop a transaction
                            if ($myProducts == false) {
                                $this->db->rollback();
                                return;
                            } else {
                                $successId[] = $deleteid;
                            }
                        }
                        // Commit a transaction
                        if ($this->db->commit() == true) {
                            $this->flash->success(str_replace('###idlist###', implode(', ', $successId), $this->lang->_('default.message-bulk-delete-success')));

                            $formData['fbulkid'] = null;
                        } else {
                            $this->flash->error($this->lang->_('default.message-bulk-delete-fail'));
                        }
                    } else {
                        $this->flash->warning($this->lang->_('default.message-no-bulk-action'));
                    }
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        // Search keyword in specified field model
        $searchKeywordInData = [
            'name'
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $id = (int) $this->request->getQuery('id', null, 0);
        $name = (int) $this->request->getQuery('name', null, 0);
        $status = (int) $this->request->getQuery('status', null, 0);
        $datecreated = (int) $this->request->getQuery('datecreated', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'id' => $id,
                'name' => $name,
                'status' => $status,
                'datecreated' => $datecreated,
            ],
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $myProducts = ProductModel::getList($formData, $this->recordPerPage, $page);

        $this->bc->add($this->lang->_('title-index'), 'admin/product');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'formData' => $formData,
            'myProducts' => $myProducts,
            'recordPerPage' => $this->recordPerPage,
            'bc' => $this->bc->generate(),
            'paginator' => $myProducts,
            'paginateUrl' => $paginateUrl
        ]);
    }

    /**
     * Create action.
     *
     * @return void
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-product-create")
     */
    public function createAction()
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $displayorder = ProductModel::maximum([
                    'column' => 'displayorder'
                ]);

                $myProduct = new ProductModel();
                $myProduct->pcid = (int) $formData['pcid'];
                $myProduct->uid = (int) $this->session->get('me')->id;
                $myProduct->name = $formData['name'];
                $myProduct->status = $formData['status'];
                $myProduct->displayorder = $displayorder + 1;
                $myProduct->seodescription = $formData['seodescription'];
                $myProduct->seokeyword = $formData['seokeyword'];
                $myProduct->image = $formData['image'];

                if ($myProduct->create()) {
                    // insert to slug table
                    $mySlug = new SlugModel();
                    $mySlug->assign([
                        'uid' => $this->session->get('me')->id,
                        'slug' => Utilities::slug($formData['name']),
                        'hash' => md5(Utilities::slug($formData['name'])),
                        'objectid' => $myProduct->id,
                        'model' => SlugModel::MODEL_PRODUCT,
                        'status' => SlugModel::STATUS_ENABLE
                    ]);
                    if (!$mySlug->create()) {
                        foreach ($mySlug->getMessages() as $msg) {
                            $this->flash->error($msg);
                        }
                    }

                    $formData = [];
                    $this->flash->success(str_replace('###name###', $myProduct->name, $this->lang->_('message-create-product-success')));
                } else {
                    foreach ($myProduct->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/product');
        $this->bc->add($this->lang->_('title-create'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => ProductModel::getStatusList(),
            'categories' => ProductCategory::find(['order' => 'lft'])
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-product-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());
                $myProduct = ProductModel::findFirst([
                    'id = :id:',
                    'bind' => ['id' => (int) $id]
                ]);

                // Delete old image when product change image cover
                if ($formData['image'] != "") {
                    if ($myProduct->image != "" && $myProduct->image != $formData['image']) {
                        $this->file->delete($myProduct->image);
                        $this->file->delete($myProduct->getThumbnailImage());
                        $this->file->delete($myProduct->getMediumImage());
                    }
                }

                $myProduct->pcid = (int) $formData['pcid'];
                $myProduct->uid = (int) $this->session->get('me')->id;
                $myProduct->name = $formData['name'];
                $myProduct->status = $formData['status'];
                $myProduct->seodescription = $formData['seodescription'];
                $myProduct->seokeyword = $formData['seokeyword'];
                $myProduct->image = $formData['image'];

                if ($myProduct->update()) {
                    $this->flash->success(str_replace('###name###', $myProduct->name, $this->lang->_('message-update-product-success')));
                } else {
                    foreach ($myProduct->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        /**
         * Find product by id
         */
        $myProduct = ProductModel::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myProduct->toArray();
        $formData['thumbnailImage'] = $myProduct->getThumbnailImage();

        $this->bc->add($this->lang->_('title-index'), 'admin/product');
        $this->bc->add($this->lang->_('title-edit'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => ProductModel::getStatusList(),
            'categories' => ProductCategory::find(['order' => 'lft'])
        ]);
    }

    /**
     * Delete product action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-product-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myProduct = ProductModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]]);

        // delete slug
        SlugModel::findFirst([
            'objectid = :id: AND model = "Product"',
            'bind' => ['id' => $myProduct->id]
        ])->delete();

        // delete cover
        if ($myProduct->image != "") {
            $this->file->delete($myProduct->image);
        }

        if ($myProduct->delete()) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myProduct->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/product');
    }

    /**
     * Upload image action.
     *
     * @return void
     *
     * @Post("/uploadimage", name="admin-product-uploadimage")
     */
    public function uploadimageAction()
    {
        $meta = $result = [];
        $myProduct = new ProductModel();
        $upload = $myProduct->processUpload();

        if ($upload == $myProduct->isSuccessUpload()) {
            $meta['status'] = true;
            $meta['message'] = 'Upload successfully.';
            $result = $myProduct->getInfo();
        } else {
            $meta['success'] = false;
            $meta['message'] = $myProduct->getMessage();
        }

        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }

    /**
     * Delete image action.
     *
     * @return void
     *
     * @Post("/deleteimage", name="admin-product-deleteimage")
     */
    public function deleteimageAction()
    {
        $meta = $result = [];
        $deleted = false;
        $fileName = $this->request->getPost('name');
        $arrayToDelete = $this->session->get($fileName);

        foreach ($arrayToDelete as $path) {
            if ($this->file->delete($path)) {
                $deleted = true;
            }
        }

        if ($deleted) {
            $meta['status'] = true;
            $meta['message'] = 'File removed!';
            $result = $arrayToDelete[0];
        } else {
            $meta['success'] = false;
            $meta['message'] = 'Error occurred when delete uploaded file!!!';
        }

        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }
}
