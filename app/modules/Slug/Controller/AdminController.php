<?php
namespace Slug\Controller;

use Core\Controller\AbstractAdminController;
use Core\Model\Slug as SlugModel;
use Core\Helper\Utilities;

/**
 * Slug admin home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/slug", name="slug-admin-home")
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
     * @Route("/", methods={"GET", "POST"}, name="admin-slug-index")
     */
    public function indexAction()
    {
        $currentUrl = $this->getCurrentUrl();
        $formData = $jsonData = [];

        // Search keyword in specified field model
        $searchKeywordInData = [
            'slug',
            'model'
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $model = (int) $this->request->getQuery('model', null, 0);
        $slug = (int) $this->request->getQuery('slug', null, 0);
        $status = (int) $this->request->getQuery('status', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'model' => $model,
                'slug' => $slug,
                'status' => $status
            ],
        ];
        $formData['orderBy'] = $orderBy;
        $formData['orderType'] = $orderType;

        $paginateUrl = $currentUrl . '?orderby=' . $formData['orderBy'] . '&ordertype=' . $formData['orderType'];
        if ($formData['conditions']['keyword'] != '') {
            $paginateUrl .= '&keyword=' . $formData['conditions']['keyword'];
        }

        $mySlugs = SlugModel::getList($formData, $this->recordPerPage, $page);

        $this->bc->add($this->lang->_('title-index'), 'admin/slug');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'formData' => $formData,
            'mySlugs' => $mySlugs,
            'recordPerPage' => $this->recordPerPage,
            'bc' => $this->bc->generate(),
            'paginator' => $mySlugs,
            'paginateUrl' => $paginateUrl
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

                // Delete old image when user change avatar
                if ($formData['avatar'] != "") {
                    if ($myUser->avatar != "" && $myUser->avatar != $formData['avatar']) {
                        $this->file->delete($myUser->avatar);
                        $this->file->delete($myUser->getThumbnailImage());
                        $this->file->delete($myUser->getMediumImage());
                    }
                }

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
        $this->bc->add($this->lang->_('title-edit'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => UserModel::getStatusList(),
            'roleList' => UserModel::getRoleList()
        ]);
    }

    /**
     * Update slug via ajax x-editable
     *
     * @return void
     *
     * @Route("/updateurl", methods={"POST"}, name="admin-slug-update")
     */
    public function updateurlAction()
    {
        $status = $msg = "";
        $id = (int) $this->request->getPost('pk', null, 0);
        $newSlug = (string) $this->request->getPost('value', null, "");

        // Check duplicate slug
        $existed = SlugModel::findFirst([
            'hash = :hash:',
            'bind' => [
                'hash' => md5($newSlug)
            ]
        ]);

        if ($existed) {
            $status = 'error';
            $msg = $this->lang->_('message-slug-unique');
        } else {
            $mySlug = SlugModel::findFirst([
                'id = :id:',
                'bind' => ['id' => $id]
            ]);

            if ($mySlug) {
                $mySlug->slug = $newSlug;
                $mySlug->hash = md5($newSlug);
                $mySlug->uid = $this->session->get('me')->id;
                if ($mySlug->update()) {
                    $status = 'success';
                    $msg = $this->lang->_('message-updateurl-ok');
                } else {
                    $status = 'error';
                    foreach ($mySlug->getMessages() as $message) {
                        $msg .= $message;
                    }
                }
            } else {
                $status = 'error';
                $msg = $this->lang->_('message-slug-notfound');
            }
        }

        $this->view->setVars([
            'status' => $status,
            'msg' => $msg
        ]);
    }
}
