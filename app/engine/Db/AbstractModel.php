<?php
namespace Engine\Db;

use Phalcon\DI;
use Phalcon\Mvc\Model as PhModel;
use Phalcon\Mvc\Model\Query\Builder as PhBuilder;
use Phalcon\Paginator\Adapter\QueryBuilder as PhQueryBuilder;

/**
 * Abstract Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @method \Engine\Behaviour\DIBehaviour|\Phalcon\DI getDI()
 */
abstract class AbstractModel extends PhModel
{
    public function initialize()
    {
        self::getTableName();
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public static function getTableName()
    {
        $reader = DI::getDefault()->get('annotations');
        $reflector = $reader->get(get_called_class());
        $annotations = $reflector->getClassAnnotations();

        return $annotations->get('Source')->getArgument(0);
    }

    /**
     * Paginator.
     * @param  [array] $params Condition query
     * @param  [integer] $limit  Limit page
     * @param  [integer] $offset Offset page
     * @return [object] Paginator object
     */
    public static function runPaginate($params, $limit, $offset)
    {
        $builder = new PhBuilder($params);

        $paginator = new PhQueryBuilder([
            'builder' => $builder,
            'limit' => $limit,
            'page' => $offset
        ]);

        return $paginator->getPaginate();
    }

    /**
     * Returns the DI container
     */
    public function getDI()
    {
        return \Phalcon\DI\FactoryDefault::getDefault();
    }
}
