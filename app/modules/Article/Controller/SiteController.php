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
     * @Route("{slug:[a-zA-Z0-9\-\_]+}", methods={"GET"}, name="site-article-product-list")
     */
    public function listAction($slug = "")
    {
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
            $formData['columns'] = '*';
            $formData['conditions'] = [
                'keyword' => '',
                'searchKeywordIn' => [],
                'filterBy' => []
            ];
            $formData['orderBy'] = 'id';
            $formData['orderType'] = 'asc';

            switch ($mySlug->model) {
                case SlugModel::MODEL_CATEGORY:
                    $myArticles = ArticleModel::getList($formData, $this->articleRecordPerPage, 1);
                    $this->view->setVars([
                        'myArticles' => $myArticles
                    ]);
                    break;
                case SlugModel::MODEL_PCATEGORY:
                    $myProducts = ProductModel::getList($formData, $this->productRecordPerPage, 1);
                    $this->view->setVars([
                        'myProducts' => $myProducts
                    ]);
                    break;
            }

            $myCategories = CategoryModel::find([
                'lft > 1 AND status = ' . CategoryModel::STATUS_ENABLE,
                'oder' => 'lft'
            ]);

            $this->view->setVars([
                'myCategories' =>  $myCategories
            ]);
        } else {
            return $this->response->redirect('notfound');
        }
    }

    public function loadarticle()
    {

    }

    public function loadproduct()
    {

    }

    /**
     * Detail of article / product
     *
     * @return void
     *
     * @Route("/{category:[a-zA-Z0-9\-\_]+}/{slug:[a-zA-Z0-9\-\_]+}", methods={"GET"}, name="site-article-product-list")
     */
    public function detailAction($category = "", $slug = "")
    {
        var_dump($category, $slug);

        die;
    }
}
