<?php
namespace Product\Controller;

use Core\Controller\AbstractAdminController;
use Product\Model\Product as ProductModel;

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

                $myProduct = new ProductModel();
                $myProduct->assign($formData);
                $myProduct->password = $this->security->hash($formData['password']);

                if ($myProduct->create()) {
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
            'statusList' => ProductModel::getStatusList()
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

                // Delete old image when user change avatar
                if ($myProduct->avatar != $formData['avatar']) {
                    if ($myProduct->avatar != "") {
                        $this->file->delete($myProduct->avatar);
                        $this->file->delete($myProduct->getThumbnailImage());
                        $this->file->delete($myProduct->getMediumImage());
                    }
                } else {
                    $formData['avatar'] = "";
                }

                $myProduct->assign($formData);
                if ($myProduct->update()) {
                    $this->flash->success(str_replace('###name###', $myProduct->name, $this->lang->_('message-update-user-success')));
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
         * Find user by id
         */
        $myProduct = ProductModel::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myProduct->toArray();
        $formData['thumbnailImage'] = $myProduct->getThumbnailImage();

        $this->bc->add($this->lang->_('title-index'), 'admin/user');
        $this->bc->add($this->lang->_('title-create'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => ProductModel::getStatusList(),
            'roleList' => ProductModel::getRoleList()
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
        $myProduct = ProductModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myProduct) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myProduct->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/user');
    }

    /**
     * Upload avatar action.
     *
     * @return void
     *
     * @Post("/uploadavatar", name="admin-user-uploadavatar")
     */
    public function uploadavatarAction()
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
     * Delete avatar action.
     *
     * @return void
     *
     * @Post("/deleteavatar", name="admin-user-deleteavatar")
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

    /**
     * Admin user login action.
     *
     * @return void
     *
     * @Route("/login", methods={"GET", "POST"}, name="admin-user-login")
     */
    public function loginAction()
    {
        $redirectUrl = base64_decode($this->request->getQuery('redirect', null, ''));

        $formData = [];
        $cookie = false;
        $formData['fname'] = $this->request->getPost('fname', null, '');
        $formData['fpassword'] = $this->request->getPost('fpassword', null, '');
        $formData['fcookie'] = $this->request->getPost('fcookie', null, false);

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                if (isset($formData['fcookie']) && $formData['fcookie'] == 'remember-me') {
                    $cookie = (boolean) true;
                }

                $identity = $this->check(
                    (string) $formData['fname'],
                    (string) $formData['fpassword'],
                    $cookie,
                    true);

                if ($identity == true) {
                    if ($redirectUrl != null) {
                        return $this->response->redirect($redirectUrl, true, 301);
                    } else {
                        return $this->response->redirect('admin');
                    }
                }
            }
        }

        $this->tag->prependTitle('Login');
        $this->view->setVars([
            'formData' => $formData
        ]);
    }

    /**
     * Checking user existing in system
     *
     * @param  string  $email
     * @param  string  $password
     * @param  boolean $cookie
     * @param  boolean $log
     * @return boolean
     */
    public function check($email, $password, $cookie = false, $log = false)
    {
        $validated = false;

        $me = new \stdClass();
        $myProduct = ProductModel::findFirst([
            'email = :femail: AND status = :status:',
            'bind' => [
                'femail' => $email,
                'status' => ProductModel::STATUS_ENABLE
            ]
        ]);

        if ($myProduct) {
            if ($this->security->checkHash($password, $myProduct->password)) {
                $me->id = $myProduct->id;
                $me->email = $myProduct->email;
                $me->name = $myProduct->name;
                $me->role = $myProduct->role;
                $me->roleName = $myProduct->getRoleName();
                $me->avatar = $myProduct->getThumbnailImage();

                // create session for user
                $this->session->set('me', $me);

                // store cookie if chosen
                if ($cookie == true) {
                    $this->cookie->set('remember-me', $me->id, time() + 15 * 86400);
                }

                $validated = true;
            } else {
                $this->flash->error('Wrong password!');
            }
        } else {
            $this->flash->error('Wrong user information!');
        }

        return $validated;
    }

    /**
     * Admin user logout action.
     *
     * @return void
     *
     * @Route("/logout", methods={"GET"}, name="admin-user-logout")
     */
    public function logoutAction()
    {
        // delete cookie
        if ($this->cookie->has('remember-me')) {
            $rememberMe = $this->cookie->get('remember-me');
            $rememberMe->delete();
        }

        // remove session
        $this->session->destroy();

        return $this->response->redirect('admin/user/login');
    }
}
