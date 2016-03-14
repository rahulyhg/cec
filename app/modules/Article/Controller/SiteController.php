<?php
namespace Article\Controller;

use Core\Controller\AbstractController;
use Core\Model\Slug as SlugModel;
use Article\Model\Article as ArticleModel;
use Product\Model\Product as ProductModel;
use Category\Model\Category as CategoryModel;
use Pcategory\Model\Pcategory as PcategoryModel;

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
    protected $articleRecordPerPage = 1;
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
        $myCategories = CategoryModel::find([
            'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
            'order' => 'lft'
        ]);

        $this->view->setVars([
            'myCategories' =>  $myCategories
        ]);
    }

    /**
     * list of article / product in category.
     *
     * @return void
     *
     * @Route("{slug:[a-zA-Z0-9\-]+}", methods={"GET"}, name="site-article-product-list")
     */
    public function listAction($slug = "")
    {
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
            switch ($mySlug->model) {
                case SlugModel::MODEL_CATEGORY:
                    $formData['columns'] = '*';
                    $formData['conditions'] = [
                        'keyword' => '',
                        'searchKeywordIn' => [],
                        'filterBy' => [
                            'cid' => $mySlug->objectid,
                        ]
                    ];
                    $formData['orderBy'] = 'id';
                    $formData['orderType'] = 'asc';

                    $myArticles = ArticleModel::getList($formData, $this->articleRecordPerPage, $page);
                    $myCategory = CategoryModel::findFirst($mySlug->objectid);

                    $this->bc->add('Trang chủ', '');
                    $this->bc->add($myCategory->name, $slug);
                    $this->view->setVars([
                        'myArticles' => $myArticles,
                        'bc' => $this->bc->generate()
                    ]);
                    break;

                case SlugModel::MODEL_PCATEGORY:
                    $formData['columns'] = '*';
                    $formData['conditions'] = [
                        'keyword' => '',
                        'searchKeywordIn' => [],
                        'filterBy' => [
                            'pcid' => $mySlug->objectid,
                        ]
                    ];
                    $formData['orderBy'] = 'id';
                    $formData['orderType'] = 'asc';

                    $myProducts = ProductModel::getList($formData, $this->productRecordPerPage, $page);
                    $myProductCategory = PcategoryModel::findFirst($mySlug->objectid);

                    $this->view->setVars([
                        'myProducts' => $myProducts
                    ]);
                    break;
            }

            $myCategories = CategoryModel::find([
                'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
                'order' => 'lft'
            ]);

            $this->view->setVars([
                'myCategories' =>  $myCategories
            ]);
        } else {
            return $this->response->redirect('notfound');
        }
    }

    /**
     * Detail of article / product
     *
     * @return void
     *
     * @Route("{category:[a-zA-Z0-9\-]+}/{slug:[a-zA-Z0-9\-]+}", methods={"GET"}, name="site-article-product-list")
     */
    public function detailAction($category = "", $slug = "")
    {
        var_dump($category, $slug);

        die;
    }
}
