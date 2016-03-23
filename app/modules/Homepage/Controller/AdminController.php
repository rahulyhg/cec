<?php
namespace Homepage\Controller;

use Core\Controller\AbstractAdminController;
use Homepage\Model\Homepage as HomepageModel;

/**
 * Homepage admin home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/homepage", name="homepage-admin-home")
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
     * @Route("/", methods={"GET", "POST"}, name="admin-homepage-index")
     */
    public function indexAction()
    {
        $currentUrl = $this->getCurrentUrl();
        $formData = $jsonData = [];

        // Search keyword in specified field model
        $searchKeywordInData = [
            'title'
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $id = (int) $this->request->getQuery('id', null, 0);
        $name = (int) $this->request->getQuery('title', null, 0);
        $status = (int) $this->request->getQuery('status', null, 0);
        $datecreated = (int) $this->request->getQuery('datecreated', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'id' => $id,
                'title' => $name,
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

        $myHomepages = HomepageModel::getList($formData, $this->recordPerPage, $page);

        $this->bc->add($this->lang->_('title-index'), 'admin/homepage');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'formData' => $formData,
            'myHomepages' => $myHomepages,
            'recordPerPage' => $this->recordPerPage,
            'bc' => $this->bc->generate(),
            'paginator' => $myHomepages,
            'paginateUrl' => $paginateUrl
        ]);
    }

    /**
     * Create action.
     *
     * @return void
     *
     * @Route("/create", methods={"GET", "POST"}, name="admin-homepage-create")
     */
    public function createAction()
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $displayorder = HomepageModel::maximum([
                    'column' => 'displayorder'
                ]);
                $formData['displayorder'] = $displayorder + 1;

                $myHomepage = new HomepageModel();
                $myHomepage->assign($formData);

                if ($myHomepage->create()) {
                    $formData = [];
                    $this->flash->success(str_replace('###name###', $myHomepage->title, $this->lang->_('message-create-homepage-success')));
                } else {
                    foreach ($myHomepage->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/homepage');
        $this->bc->add($this->lang->_('title-create'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => HomepageModel::getStatusList(),
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-homepage-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());
                $myHomepage = HomepageModel::findFirst([
                    'id = :id:',
                    'bind' => ['id' => (int) $id]
                ]);

                // Delete old image when product change image cover
                if ($formData['image'] != "") {
                    if ($myHomepage->image != "" && $myHomepage->image != $formData['image']) {
                        $this->file->delete($myHomepage->image);
                        $this->file->delete($myHomepage->getThumbnailImage());
                        $this->file->delete($myHomepage->getMediumImage());
                    }
                }

                $myHomepage->assign($formData);
                if ($myHomepage->update()) {
                    $this->flash->success(str_replace('###name###', $myHomepage->title, $this->lang->_('message-update-homepage-success')));
                } else {
                    foreach ($myHomepage->getMessages() as $msg) {
                        $message .= $this->lang->_($msg->getMessage()) . '<br />';
                    }
                    $this->flash->error($message);
                }
            } else {
                $this->flash->error($this->lang->_('default.message-csrf-protected'));
            }
        }

        /**
         * Find homepage by id
         */
        $myHomepage = HomepageModel::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myHomepage->toArray();
        $formData['thumbnailImage'] = $myHomepage->getThumbnailImage();

        $this->bc->add($this->lang->_('title-index'), 'admin/homepage');
        $this->bc->add($this->lang->_('title-edit'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => HomepageModel::getStatusList()
        ]);
    }

    /**
     * Delete homepage action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-homepage-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myHomepage = HomepageModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]]);

        // delete cover
        if ($myHomepage->image != "") {
            $this->file->delete($myHomepage->image);
        }

        if ($myHomepage->delete()) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myHomepage->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/homepage');
    }

    /**
     * Upload image action.
     *
     * @return void
     *
     * @Post("/uploadimage", name="admin-homepage-uploadimage")
     */
    public function uploadimageAction()
    {
        $meta = $result = [];
        $myHomepage = new HomepageModel();
        $upload = $myHomepage->processUpload();

        if ($upload == $myHomepage->isSuccessUpload()) {
            $meta['status'] = true;
            $meta['message'] = 'Upload successfully.';
            $result = $myHomepage->getInfo();
        } else {
            $meta['success'] = false;
            $meta['message'] = $myHomepage->getMessage();
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
     * @Post("/deleteimage", name="admin-homepage-deleteimage")
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
