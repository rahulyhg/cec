<?php
namespace Article\Controller;

use Core\Controller\AbstractController;
use Core\Model\Slug as SlugModel;
use Article\Model\Article as ArticleModel;
use Product\Model\Product as ProductModel;
use Category\Model\Category as CategoryModel;
use Pcategory\Model\Pcategory as PcategoryModel;
use User\Model\Contact as ContactModel;
use Core\Helper\Utilities;

/**
 * Article site home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/", name="site-article-home")
 */
class SiteController extends AbstractController
{
    /**
     * number record on 1 page
     * @var integer
     */
    protected $articleRecordPerPage = 6;
    protected $productRecordPerPage = 8;

    /**
     * Homepage.
     *
     * @return void
     *
     * @Route("/", methods={"GET"}, name="site-article-index")
     */
    public function indexAction()
    {
        $myArticleCategories = CategoryModel::find([
            'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myCategories = [];
        foreach ($myArticleCategories as $cat) {
            if ($cat->root > 1) {
                $myCategories[$cat->root]->child[] = $cat;
            } else {
                $myCategories[$cat->id] = $cat;
            }
        }

        $myProductCategories = PcategoryModel::find([
            'lft > 1 AND status = ' . PcategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myPcategories = [];
        foreach ($myProductCategories as $cat) {
            if ($cat->root > 1) {
                $myPcategories[$cat->root]->child[] = $cat;
            } else {
                $myPcategories[$cat->id] = $cat;
            }
        }

        $myArticleActivity = ArticleModel::find([
            'conditions' => 'type = ' . ArticleModel::TYPE_ACTIVITY,
            'order' => 'id ASC',
            'limit' => 3
        ]);

        $myArticleProject = ArticleModel::find([
            'conditions' => 'type = ' . ArticleModel::TYPE_PROJECT,
            'order' => 'id ASC',
            'limit' => 3
        ]);

        $this->view->setVars([
            'myCategories' =>  $myCategories,
            'myPcategories' =>  $myPcategories,
            'myArticleActivity' => $myArticleActivity,
            'myArticleProject' => $myArticleProject
        ]);
    }

    /**
     * list of article / product in category.
     *
     * @return void
     *
     * @Route("{slug:[a-zA-Z0-9\-]+}", methods={"GET", "POST"}, name="site-article-product-list")
     */
    public function listAction($slug = "")
    {
        //Special slug
        if ($slug == 'lien-he') {
            if ($this->request->hasPost('fsubmit')) {
                $formData = [];

                if ($this->security->checkToken()) {
                    $formData = array_merge($formData, $this->request->getPost());

                    $myContact = new ContactModel();
                    $myContact->assign($formData);
                    if ($myContact->create()) {
                        $this->flash->success('Chúng tôi đã nhận được yêu cầu của quí khách. Cảm ơn.');
                    } else {
                        $this->flash->error('Quí khách vui lòng nhập lại sau.');
                    }
                } else {
                    $this->flash->error($this->lang->_('default.message-csrf-protected'));
                }
            }
            $this->bc->add('Trang chủ', '');
            $this->bc->add('Liên hệ', 'lien-he');
            $this->view->setVars([
                'bc' => $this->bc->generate()
            ]);
        } else {
            $page = $this->request->getQuery('page', null, 1);
            $formData = [];

            // if url is admin, redirect to admin dashboard
            if ($slug == "admin") {
                return $this->response->redirect('admin/dashboard');
            }

            $mySlug = SlugModel::findFirst([
                'hash = :hash: AND status = :status:',
                'bind' => [
                    'hash' => md5($slug),
                    'status' => SlugModel::STATUS_ENABLE
                ]
            ]);

            if ($mySlug) {
                $currentUrl = base64_decode(Utilities::getCurrentUrl());
                $formData['columns'] = '*';
                $formData['orderBy'] = 'id';
                $formData['orderType'] = 'asc';

                switch ($mySlug->model) {
                    case SlugModel::MODEL_CATEGORY:
                        $formData['conditions'] = [
                            'keyword' => '',
                            'searchKeywordIn' => [],
                            'filterBy' => [
                                'cid' => $mySlug->objectid,
                                'status' => CategoryModel::STATUS_ENABLE
                            ]
                        ];

                        $myArticles = ArticleModel::getList($formData, $this->articleRecordPerPage, $page);
                        $myCategory = CategoryModel::findFirst($mySlug->objectid);

                        if ($this->request->isAjax()) {
                            $this->viewmorearticle($myArticles, $myCategory);
                        } else {
                            $this->bc->add('Trang chủ', '');
                            $ancestors = $myCategory->ancestors();

                            if ($ancestors) {
                                foreach ($ancestors as $category) {
                                    if ($category->level != 1) {
                                        $this->bc->add($category->name, $category->count > 0 ? $category->getSeo()->slug : $slug . "/#");
                                    }
                                }
                            }
                            $this->bc->add($myCategory->name, $slug);
                            $this->view->setVars([
                                'myCategory' => $myCategory,
                                'myArticles' => $myArticles,
                                'bc' => $this->bc->generate(),
                                'paginateUrl' => $currentUrl
                            ]);
                        }
                        break;

                    case SlugModel::MODEL_PCATEGORY:
                        $formData['conditions'] = [
                            'keyword' => '',
                            'searchKeywordIn' => [],
                            'filterBy' => [
                                'pcid' => $mySlug->objectid,
                                'status' => PcategoryModel::STATUS_ENABLE
                            ]
                        ];

                        $myProducts = ProductModel::getList($formData, $this->productRecordPerPage, $page);
                        $myProductCategory = PcategoryModel::findFirst($mySlug->objectid);

                        if ($this->request->isAjax()) {
                            $this->viewmoreproduct($myProducts, $myProductCategory);
                        } else {
                            $this->bc->add('Trang chủ', '');
                            $ancestors = $myProductCategory->ancestors();
                            if ($ancestors) {
                                foreach ($ancestors as $category) {
                                    if ($category->level != 1) {
                                        $this->bc->add($category->name, $category->count > 0 ? $category->getSeo()->slug : $slug . "/#");
                                    }
                                }
                            }
                            $this->bc->add($myProductCategory->name, $slug);
                            $this->view->setVars([
                                'myProductCategory' => $myProductCategory,
                                'myProducts' => $myProducts,
                                'bc' => $this->bc->generate(),
                                'paginateUrl' => $currentUrl
                            ]);
                        }

                        $this->view->setVars([
                            'myProducts' => $myProducts
                        ]);
                        break;

                    case SlugModel::MODEL_ARTICLE:
                        $myArticle = ArticleModel::findFirst([
                            'id = :articleId: AND status = :status:',
                            'bind' => [
                                'articleId' => $mySlug->objectid,
                                'status' => ArticleModel::STATUS_ENABLE
                            ]
                        ]);

                        $myArticleActivity = ArticleModel::find([
                            'type = :type: AND status = :status:',
                            'bind' => [
                                'type' => ArticleModel::TYPE_ACTIVITY,
                                'status' => ArticleModel::STATUS_ENABLE
                            ]
                        ]);

                        if ($myArticle) {
                            $this->bc->add('Trang chủ', '');
                            $this->bc->add($myArticle->title, $slug);
                            $this->view->setVars([
                                'myArticle' => $myArticle,
                                'myArticleActivity' => $myArticleActivity,
                                'bc' => $this->bc->generate(),
                            ]);
                        } else {
                            return $this->response->redirect('notfound');
                        }
                        break;
                }
            } else {
                return $this->response->redirect('notfound');
            }
        }

        $myArticleCategories = CategoryModel::find([
            'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myCategories = [];
        foreach ($myArticleCategories as $cat) {
            if ($cat->root > 1) {
                $myCategories[$cat->root]->child[] = $cat;
            } else {
                $myCategories[$cat->id] = $cat;
            }
        }

        $myProductCategories = PcategoryModel::find([
            'lft > 1 AND status = ' . PcategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myPcategories = [];
        foreach ($myProductCategories as $cat) {
            if ($cat->root > 1) {
                $myPcategories[$cat->root]->child[] = $cat;
            } else {
                $myPcategories[$cat->id] = $cat;
            }
        }

        $this->view->setVars([
            'myCategories' =>  $myCategories,
            'myPcategories' =>  $myPcategories,
            'slug' => $slug
        ]);
    }

    // Ajax load more article
    public function viewmorearticle($myArticles, $myCategory)
    {
        $meta = $result = [];

        foreach ($myArticles->items as $item) {
            $result['data'][] = [
                'title' => $item->title,
                'image' => $this->url->getStaticBaseUri() . $item->getMediumImage(),
                'seodescription' => $item->seodescription,
                'slug' => $item->getSeo()->slug
            ];
        }

        $meta['status'] = true;
        // $meta['message'] = 'test ajax';
        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }

    // Ajax load more product
    public function viewmoreproduct($myProducts, $myCategory)
    {
        $meta = $result = [];

        foreach ($myProducts->items as $item) {
            $result['data'][] = [
                'name' => $item->name,
                'image' => $this->url->getStaticBaseUri() . $item->getMediumImage(),
                'seodescription' => $item->seodescription,
                'slug' => $item->getSeo()->slug
            ];
        }

        $meta['status'] = true;
        // $meta['message'] = 'test ajax';
        $this->view->setVars([
            '_meta' => $meta,
            '_result' => $result
        ]);
    }

    /**
     * Homepage.
     *
     * @return void
     *
     * @Route("notfound", methods={"GET"}, name="site-article-index")
     */
    public function notfoundAction()
    {
        $this->response->setStatusCode('404', 'Page not found');
    }
}
