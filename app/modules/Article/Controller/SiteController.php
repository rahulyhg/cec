<?php
namespace Article\Controller;

use Core\Controller\AbstractController;

/**
 * Article site home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 */
class SiteController extends AbstractController
{
    /**
     * number record on 1 page
     * @var integer
     */
    protected $recordPerPage = 30;

    /**
     * Homepage.
     *
     * @return void
     *
     * @Route("/", methods={"GET"}, name="site-article-index")
     */
    public function indexAction()
    {

    }

    /**
     * Category list of article / product.
     *
     * @return void
     *
     * @Route("/{slug:[a-zA-Z0-9\-\_]+}", methods={"GET"}, name="site-article-product-list")
     */
    public function listAction($slug = "")
    {
        if ($slug == "admin") {
            return $this->response->redirect('admin/dashboard');
        }


        echo md5('test1231');
        die;
    }


}
