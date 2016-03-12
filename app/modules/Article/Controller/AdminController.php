<?php
namespace Article\Controller;

use Core\Controller\AbstractAdminController;
use Article\Model\Article as ArticleModel;
use Core\Model\Image as ImageModel;

/**
 * Article admin home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/article", name="article-admin-home")
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
     * @Route("/", methods={"GET", "POST"}, name="admin-article-index")
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
                            $myUsers = UserModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

                            // If fail stop a transaction
                            if ($myUsers == false) {
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

        $myUsers = UserModel::getList($formData, $this->recordPerPage, $page);

        $this->bc->add($this->lang->_('title-index'), 'admin/user');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'formData' => $formData,
            'myUsers' => $myUsers,
            'recordPerPage' => $this->recordPerPage,
            'bc' => $this->bc->generate(),
            'paginator' => $myUsers,
            'paginateUrl' => $paginateUrl
        ]);
    }

    /**
     * Create action.
     *
     * @return void
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-article-create")
     */
    public function createAction()
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            var_dump($this->request->getPost());
            die;
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myArticle = new ArticleModel();
                $myArticle->assign($formData);

                if ($myArticle->create()) {
                    $formData = [];
                    $this->flash->success(str_replace('###name###', $myArticle->title, $this->lang->_('message-create-user-success')));
                } else {
                    foreach ($myArticle->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/article');
        $this->bc->add($this->lang->_('title-create'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => ArticleModel::getStatusList()
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-user-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myUser = UserModel::findFirst([
                    'id = :id:',
                    'bind' => ['id' => (int) $id]
                ]);
                $myUser->assign($formData);

                if ($myUser->update()) {
                    $this->flash->success(str_replace('###name###', $myUser->name, $this->lang->_('message-update-user-success')));
                } else {
                    foreach ($myUser->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        /**
         * Find user by id
         */
        $myUser = UserModel::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myUser->toArray();
        $formData['thumbnailImage'] = $myUser->getThumbnailImage();

        $this->bc->add($this->lang->_('title-index'), 'admin/user');
        $this->bc->add($this->lang->_('title-create'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => UserModel::getStatusList(),
            'roleList' => UserModel::getRoleList()
        ]);
    }

    /**
     * Delete user action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-user-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myUser = UserModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myUser) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myUser->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/user');
    }

    /**
     * Upload image action.
     *
     * @return void
     *
     * @Post("/uploadimage", name="admin-article-uploadimage")
     */
    public function uploadimageAction()
    {
        $meta = $result = [];
        $myImage = new ImageModel();
        $upload = $myImage->processUpload();

        if ($upload == $myImage->isSuccessUpload()) {
            $meta['status'] = true;
            $meta['message'] = 'Upload successfully.';
            $result = $myImage->getInfo();
        } else {
            $meta['success'] = false;
            $meta['message'] = $myImage->getMessage();
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
     * @Post("/deleteimage", name="admin-article-deleteimage")
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
            $meta['message'] = 'Remove uploaded file successfully.';
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
