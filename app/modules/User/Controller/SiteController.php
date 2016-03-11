<?php
namespace User\Controller;

use Core\Controller\AbstractAdminController;
use User\Model\User as UserModel;

/**
 * User site home.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @RoutePrefix("/admin/login", name="user-site-home")
 */
class SiteController extends AbstractAdminController
{
    /**
     * Main action.
     *
     * @return void
     *
     * @Route("/", methods={"GET", "POST"}, name="user-site-index")
     */
    public function loginAction()
    {

    }
}
