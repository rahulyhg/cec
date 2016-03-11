<?php
namespace Engine;

use Phalcon\DI;
use Phalcon\Events\Manager;
use Phalcon\Mvc\View as PhView;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Engine\View\Extension as EnViewExtension;

/**
 * View factory.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class View extends PhView
{
    /**
     * Create view instance.
     * If no events manager provided - events would not be attached.
     *
     * @param DIBehaviour  $di             DI.
     * @param Config       $config         Configuration.
     * @param string       $viewsDirectory Views directory location.
     * @param Manager|null $em             Events manager.
     *
     * @return View
     */
    public static function factory($di, $config, $viewsDirectory, $em = null)
    {
        $view = new PhView();
        $volt = new PhVolt($view, $di);
        $volt->setOptions(
            [
                "compiledPath" => $config->global->view->compiledPath,
                "compiledExtension" => $config->global->view->compiledExtension,
                'compiledSeparator' => $config->global->view->compiledSeparator,
                'compileAlways' => $config->global->view->compileAlways
            ]
        );

        $compiler = $volt->getCompiler();
        $compiler->addExtension(new EnViewExtension());
        $compiler->addFilter('floor', 'floor');
        $compiler->addFunction('range', 'range');
        $compiler->addFunction('in_array', 'in_array');
        $compiler->addFunction('count', 'count');
        $compiler->addFunction('str_repeat', 'str_repeat');
        $view
            ->registerEngines(['.volt' => $volt])
            ->setRenderLevel(PhView::LEVEL_ACTION_VIEW)
            ->setViewsDir($viewsDirectory);

        // Attach a listener for type "view".
        if ($em) {
            $em->attach(
                "view",
                function ($event, $view) use ($di, $config) {
                    if ($config->global->profiler && $di->has('profiler')) {
                        if ($event->getType() == 'beforeRender') {
                            $di->get('profiler')->start();
                        }
                        if ($event->getType() == 'afterRender') {
                            $di->get('profiler')->stop($view->getActiveRenderPath(), 'view');
                        }
                    }
                    if ($event->getType() == 'notFoundView') {
                        throw new Exception('View not found - "' . $view->getActiveRenderPath() . '"');
                    }
                }
            );
            $view->setEventsManager($em);
        }

        $di->set('view', $view);

        return $view;
    }
}
