<?php
namespace Article\Controller;

use Core\Controller\AbstractAdminController;
use Article\Model\Article as ArticleModel;
use Core\Model\Image as ImageModel;
use Category\Model\Category;
use Core\Helper\Utilities;

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
                            $myArticle = ArticleModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $deleteid]])->delete();

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
            'title'
        ];
        $page = (int) $this->request->getQuery('page', null, 1);
        $orderBy = (string) $this->request->getQuery('orderby', null, 'id');
        $orderType = (string) $this->request->getQuery('ordertype', null, 'asc');
        $keyword = (string) $this->request->getQuery('keyword', null, '');
        // optional Filter
        $id = (int) $this->request->getQuery('id', null, 0);
        $status = (int) $this->request->getQuery('status', null, 0);
        $datecreated = (int) $this->request->getQuery('datecreated', null, 0);
        $formData['columns'] = '*';
        $formData['conditions'] = [
            'keyword' => $keyword,
            'searchKeywordIn' => $searchKeywordInData,
            'filterBy' => [
                'id' => $id,
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

        $myArticles = ArticleModel::getList($formData, $this->recordPerPage, $page);

        $this->bc->add($this->lang->_('title-index'), 'admin/article');
        $this->bc->add($this->lang->_('title-listing'), '');
        $this->view->setVars([
            'formData' => $formData,
            'myArticles' => $myArticles,
            'recordPerPage' => $this->recordPerPage,
            'bc' => $this->bc->generate(),
            'paginator' => $myArticles,
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
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $displayorder = ArticleModel::maximum([
                    'column' => 'displayorder'
                ]);

                $myArticle = new ArticleModel();
                $myArticle->cid = (int) $formData['cid'];
                $myArticle->uid = (int) $this->session->get('me')->id;
                $myArticle->title = $formData['title'];
                // $myArticle->slug = Utilities::slug($formData['title']);
                $myArticle->content = $formData['content'];
                $myArticle->status = $formData['status'];
                $myArticle->displayorder = $displayorder + 1;
                $myArticle->displaytohome = $formData['displaytohome'];
                $myArticle->type = $formData['type'];
                $myArticle->seodescription = $formData['seodescription'];
                $myArticle->seokeyword = $formData['seokeyword'];
                $myArticle->image = $formData['image'];

                if ($myArticle->create()) {
                    // insert to image table.
                    if (isset($formData['uploadfiles']) && count($formData['uploadfiles']) > 0) {
                        $imageList = array_unique($formData['uploadfiles']);

                        foreach ($imageList as $image) {
                            $path_parts = pathinfo(PUBLIC_PATH . $image);

                            $myImage = new ImageModel();
                            $myImage->assign([
                                'aid' => $myArticle->id,
                                'name' => $myArticle->title,
                                'path' => $image,
                                'filename' => $path_parts['filename'],
                                'basename' => $path_parts['basename'],
                                'extension' => $path_parts['extension'],
                                'size' => $this->file->getSize($image),
                                'status' => ImageModel::STATUS_ENABLE
                            ]);
                            $myImage->create();
                        }
                    }

                    $formData = []; //Reset form
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
            'statusList' => ArticleModel::getStatusList(),
            'typeList' => ArticleModel::getTypeList(),
            'ishomeList' => ArticleModel::getDisplayToHomeList(),
            'categories' => Category::find(['order' => 'lft'])
        ]);
    }

    /**
     * Edit action.
     *
     * @return void
     *
     * @Route("/edit/{id:[0-9]+}", methods={"GET", "POST"}, name="admin-article-edit")
     */
    public function editAction($id = 0)
    {
        $formData = [];
        $message = '';

        if ($this->request->hasPost('fsubmit')) {
            if ($this->security->checkToken()) {
                $formData = array_merge($formData, $this->request->getPost());

                $myArticle = ArticleModel::findFirst([
                    'id = :id:',
                    'bind' => ['id' => (int) $id]
                ]);

                // Delete old image when user change image cover
                if ($myArticle->image != $formData['image']) {
                    if ($myArticle->image != "") {
                        $this->file->delete($myArticle->image);
                        $this->file->delete($myArticle->getThumbnailImage());
                        $this->file->delete($myArticle->getMediumImage());
                    }
                } else {
                    $formData['image'] = "";
                }

                $myArticle->cid = (int) $formData['cid'];
                $myArticle->uid = (int) $this->session->get('me')->id;
                $myArticle->title = $formData['title'];
                // $myArticle->slug = Utilities::slug($formData['title']);
                $myArticle->content = $formData['content'];
                $myArticle->status = $formData['status'];
                $myArticle->displaytohome = $formData['displaytohome'];
                $myArticle->type = $formData['type'];
                $myArticle->seodescription = $formData['seodescription'];
                $myArticle->seokeyword = $formData['seokeyword'];
                $myArticle->image = $formData['image'];

                if ($myArticle->update()) {
                    // insert to image table.
                    if (isset($formData['uploadfiles']) && count($formData['uploadfiles']) > 0) {
                        $imageList = array_unique($formData['uploadfiles']);

                        foreach ($imageList as $image) {
                            $path_parts = pathinfo(PUBLIC_PATH . $image);

                            $myImage = new ImageModel();
                            $myImage->assign([
                                'aid' => $myArticle->id,
                                'name' => $myArticle->title,
                                'path' => $image,
                                'filename' => $path_parts['filename'],
                                'basename' => $path_parts['basename'],
                                'extension' => $path_parts['extension'],
                                'size' => $this->file->getSize($image),
                                'status' => ImageModel::STATUS_ENABLE
                            ]);
                            $myImage->create();
                        }
                    }

                    $this->flash->success(str_replace('###name###', $myArticle->title, $this->lang->_('message-update-user-success')));
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

        /**
         * Find article by id
         */
        $myArticle = ArticleModel::findFirst([
            'id = :id:',
            'bind' => ['id' => (int) $id]
        ]);

        $formData = $myArticle->toArray();
        $formData['thumbnailImage'] = $myArticle->getThumbnailImage();

        // Get article gallery
        $formData['imageList'] = [];
        $myImages = ImageModel::find([
            'aid = :articleId:',
            'bind' => ['articleId' => (int) $id]
        ]);

        if ($myImages) {
            foreach ($myImages as $image) {
                $formData['imageList'][] = [
                    'name' => $image->basename,
                    'path' => $image->path,
                    'size' => $image->size
                ];
            }
            $formData['imageList'] = (string) json_encode($formData['imageList']);
        }

        $this->bc->add($this->lang->_('title-index'), 'admin/article');
        $this->bc->add($this->lang->_('title-edit'), '');
        $this->view->setVars([
            'formData' => $formData,
            'bc' => $this->bc->generate(),
            'statusList' => ArticleModel::getStatusList(),
            'typeList' => ArticleModel::getTypeList(),
            'ishomeList' => ArticleModel::getDisplayToHomeList(),
            'categories' => Category::find(['order' => 'lft'])
        ]);
    }

    /**
     * Delete article action.
     *
     * @return void
     *
     * @Get("/delete/{id:[0-9]+}", name="admin-article-delete")
     */
    public function deleteAction($id = 0)
    {
        $message = '';
        $myArticle = ArticleModel::findFirst(['id = :id:', 'bind' => ['id' => (int) $id]])->delete();

        if ($myArticle) {
            $this->flash->success(str_replace('###id###', $id, $this->lang->_('message-delete-success')));
        } else {
            foreach ($myArticle->getMessages() as $msg) {
                $message .= $this->lang->_($msg->getMessage()) . "</br>";
            }
            $this->flashSession->error($message);
        }

        return $this->response->redirect('admin/article');
    }

    /**
     * Upload image gallery action.
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
            $meta['message'] = 'File uploaded!';
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
     * Delete image gallery action.
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
        $isEdit = $this->request->getPost('edit', null, 0);

        if ($isEdit > 0) {
            $myImage = ImageModel::findFirst([
                'basename = :name:',
                'bind' => ['name' => $fileName]
            ]);
            if ($myImage) {
                $this->file->delete($myImage->path);
                $this->file->delete($myImage->getMediumImage());
                $this->file->delete($myImage->getThumbnailImage());
                $myImage->delete();
                $meta['status'] = true;
                $meta['message'] = 'File removed!';
            } else {
                $meta['success'] = false;
                $meta['message'] = 'Image file not found!!!';
            }
        } else {
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
        }

        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }
}
