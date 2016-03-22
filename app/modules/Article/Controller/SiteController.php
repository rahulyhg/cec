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
use Company\Model\Company as CompanyModel;

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
        $articleRootId = CategoryModel::findFirstByLft(1)->id;
        $myArticleCategories = CategoryModel::find([
            'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myCategories = [];
        foreach ($myArticleCategories as $cat) {
            if ($cat->root > $articleRootId) {
                $myCategories[$cat->root]->child[] = $cat;
            } else {
                $myCategories[$cat->id] = $cat;
            }
        }

        $productRootId = PcategoryModel::findFirstByLft(1)->id;
        $myProductCategories = PcategoryModel::find([
            'lft > 1 AND status = ' . PcategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myPcategories = [];
        foreach ($myProductCategories as $cat) {
            if ($cat->root > $productRootId) {
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
            'myArticleProject' => $myArticleProject,
            'myCompany' => CompanyModel::findFirst()
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
            $formData = [];
            if ($this->request->hasPost('fsubmit')) {
                if ($this->security->checkToken()) {
                    $formData = array_merge($formData, $this->request->getPost());

                    $myContact = new ContactModel();
                    $myContact->assign($formData);
                    if ($myContact->create()) {
                        $formData = [];
                        $this->flash->success('Chúng tôi đã nhận được yêu cầu của quí khách. Cảm ơn.');
                    } else {
                        foreach ($myContact->getMessages() as $msg) {
                            $this->flash->error($this->lang->_($msg->getMessage()));
                        }
                    }
                } else {
                    $this->flash->error($this->lang->_('default.message-csrf-protected'));
                }
            }
            $this->bc->add('Trang chủ', '');
            $this->bc->add('Liên hệ', 'lien-he');
            $this->view->setVars([
                'formData' => $formData,
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

                        // if only 1 article, go to it
                        $myArticlesCount = ArticleModel::count([
                            'cid = :cid:',
                            'bind' => [
                                'cid' => $mySlug->objectid
                            ]
                        ]);
                        if ($myArticlesCount == 1) {
                            return $this->response->redirect(
                                ArticleModel::findFirst([
                                    'cid = :cid:',
                                    'bind' => [
                                        'cid' => $mySlug->objectid
                                    ]
                                ])->getSeo()->slug,
                                true,
                                301
                            );
                        }

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

        $articleRootId = CategoryModel::findFirstByLft(1)->id;
        $myArticleCategories = CategoryModel::find([
            'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myCategories = [];
        foreach ($myArticleCategories as $cat) {
            if ($cat->root > $articleRootId) {
                $myCategories[$cat->root]->child[] = $cat;
            } else {
                $myCategories[$cat->id] = $cat;
            }
        }

        $productRootId = PcategoryModel::findFirstByLft(1)->id;
        $myProductCategories = PcategoryModel::find([
            'lft > 1 AND status = ' . PcategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);
        $myPcategories = [];
        foreach ($myProductCategories as $cat) {
            if ($cat->root > $productRootId) {
                $myPcategories[$cat->root]->child[] = $cat;
            } else {
                $myPcategories[$cat->id] = $cat;
            }
        }

        $this->view->setVars([
            'myCategories' =>  $myCategories,
            'myPcategories' =>  $myPcategories,
            'slug' => $slug,
            'myCompany' => CompanyModel::findFirst()
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
     * load geo code.
     *
     * @return void
     *
     * @Route("getmap", methods={"GET"}, name="site-article-getmap")
     */
    public function getmapAction()
    {
        $address = $this->request->getQuery('address');

        // url encode the address
        $address = urlencode($address);

        // google map geocode api url
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address
        if($resp['status']=='OK'){

            // get the important data
            $lati = $resp['results'][0]['geometry']['location']['lat'];
            $longi = $resp['results'][0]['geometry']['location']['lng'];
            $formatted_address = $resp['results'][0]['formatted_address'];

            // verify if data is complete
            if($lati && $longi && $formatted_address){

                // put the data in the array
                $data_arr = array();

                array_push(
                    $data_arr,
                        $lati,
                        $longi,
                        $formatted_address
                    );

                $this->view->setVars([
                    '_result' => $data_arr
                ]);
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    /**
     * not found page.
     *
     * @return void
     *
     * @Route("notfound", methods={"GET"}, name="site-article-notfound")
     */
    public function notfoundAction()
    {
        $this->response->setStatusCode('404', 'Page not found');
    }

    /**
     * return html slider
     *
     * @return void
     *
     * @Route("getslide", methods={"GET"}, name="site-article-getslide")
     */
    public function getslideAction()
    {
        $html = '';
        $html .= '<div class="wrapgalleria">';
        $html .= '<a href="javascript:void(0)" class="closed">╳</a>';
        $html .= '    <div id="galleria" class="owl-carousel owl-theme">';

        $myArticleList = ArticleModel::find([
            'type = :type:',
            'bind' => [
                'type' => ArticleModel::TYPE_GALLERY
            ]
        ]);

        if ($myArticleList) {
            foreach ($myArticleList as $item) {
                $html .= '<div class="item"><img src="'. $this->url->getStaticBaseUri() . $item->image .'" alt=""></div>';
            }
        }

        $html .= '    </div>';
        $html .= '</div>';
        $html .= '<div class="overlay"></div>';
        echo $html;
        exit();
        // $this->view->setVars([
        //     '_result' => $html
        // ]);
    }
}
