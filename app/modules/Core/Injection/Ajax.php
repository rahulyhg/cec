<?php
namespace Core\Injection;

use Engine\Injection\AbstractInjection;
use Phalcon\DI;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;

/**
 * Ajax Response Injection.
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Ajax extends AbstractInjection
{
    /**
     * This action is executed after execute any action in the application.
     *
     * @param PhalconEvent $event      Event object.
     * @param Dispatcher   $dispatcher Dispatcher object.
     *
     * @return mixed
     */
    public function afterExecuteRoute(PhEvent $event, Dispatcher $dispatcher)
    {
        if ($this->getDI()->get('request')->isAjax() == true) {
            $this->getDI()->get('view')->disableLevel([
                View::LEVEL_ACTION_VIEW => true,
                View::LEVEL_LAYOUT => true,
                View::LEVEL_MAIN_LAYOUT => true,
                View::LEVEL_AFTER_TEMPLATE => true,
                View::LEVEL_BEFORE_TEMPLATE => true
            ]);

            $this->getDI()->get('response')->setContentType('application/json', 'UTF-8');
            $data = $this->getDI()->get('view')->getParamsToView();

            /* Set global params if is not set in controller/action */
            if (is_array($data)) {
                $data['_meta'] = isset($data['_meta']) ? $data['_meta'] : [];
                $data['_result'] = isset($data['_result']) ? $data['_result'] : [];
                $data = json_encode($data);
            }

            $this->getDI()->get('response')->setContent($data);
        }

        return $this->getDI()->get('response')->send();
    }
}
