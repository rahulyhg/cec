<?php
namespace Core\Injection;

use Engine\Injection\AbstractInjection;
use Phalcon\Acl\Adapter\Memory as PhAclMemory;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Acl as PhAcl;
use Phalcon\DI;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Mvc\Dispatcher;
use User\Model\User as UserModel;

/**
 * Access Control List Injection.
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Acl extends AbstractInjection
{
    const
        /**
         * Acl cache key.
         */
        CACHE_KEY_ACL = 'acl.cache';

    /**
     * Acl adapter.
     *
     * @var AclMemory
     */
    protected $_acl;

    /**
     * Get acl system.
     *
     * @return AclMemory
     */
    public function getAcl($config)
    {
        $permission = $config->permission->toArray();

        if (!$this->_acl) {
            $cacheData = $this->getDI()->get('cacheData');
            $acl = $cacheData->get(self::CACHE_KEY_ACL);

            if ($acl === null) {
                $acl = new PhAclMemory();
                $acl->setDefaultAction(PhAcl::DENY);

                $groupList = array_keys($permission);
                foreach ($groupList as $groupConst => $groupValue) {
                    // Add Role
                    $acl->addRole(new Role((string) $groupValue));

                    if (isset($permission[$groupValue]) && is_array($permission[$groupValue]) == true) {
                        foreach ($permission[$groupValue] as $group => $controller) {
                            foreach ($controller as $action) {
                                $actionArr = explode('/', $action);
                                $resource = strtolower($group) . '/' . $actionArr[0];

                                // Add Resource
                                $acl->addResource($resource, $actionArr[1]);

                                // Grant role to resource
                                $acl->allow($groupValue, $resource, $actionArr[1]);
                            }
                        }
                    }
                }

                $cacheData->save(self::CACHE_KEY_ACL, $acl, 2592000); // 30 days cache.
            }
            $this->_acl = $acl;
        }

        return $this->_acl;
    }

    /**
     * This action is executed before execute any action in the application.
     *
     * @param PhalconEvent $event      Event object.
     * @param Dispatcher   $dispatcher Dispatcher object.
     *
     * @return mixed
     */
    public function beforeDispatch(PhEvent $event, Dispatcher $dispatcher)
    {
        $me = null;

        $config = $this->getDI()->get('config');
        $cookie = $this->getDI()->get('cookie');
        $session = $this->getDI()->get('session');

        // check exsited cookie
        if ($cookie->has('remember-me')) {
            $rememberMe = $cookie->get('remember-me');
            $userId = $rememberMe->getValue();
            $myUser = UserModel::findFirst([
                'id = :id: AND status = :status:',
                'bind' => [
                    'id' => $userId,
                    'status' => UserModel::STATUS_ENABLE
                ]
            ]);
            if ($myUser) {
                $me =  new \stdClass();
                $me->id = $myUser->id;
                $me->email = $myUser->email;
                $me->name = $myUser->name;
                $me->role = $myUser->role;
                $me->roleName = $myUser->getRoleName();
                $me->avatar = $myUser->avatar;
            }

            $session->set('me', $me);
            $role = $myUser->role;
        } else {
            //Get role name from session
            if ($session->has('me')) {
                $me = $session->get('me');
                $role = $me->role;
            } else {
                $role = ROLE_GUEST;
            }
        }

        $current_resource = $dispatcher->getModuleName() . '/' . strtolower($dispatcher->getControllerName());
        $current_action = $dispatcher->getActionName();

        $acl = $this->getAcl($config);
        $allowed = (int) $acl->isAllowed($role, $current_resource, $current_action);
        // var_dump($allowed, $current_resource, $current_action, $me);die;

        if ($allowed !== PhAcl::ALLOW) {
            $this->getDI()->getEventsManager()->fire(
                'dispatch:beforeException',
                $dispatcher,
                new Dispatcher\Exception()
            );
        }

        return !$event->isStopped();
    }
}
