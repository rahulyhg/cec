<?php
namespace Pcategory\Model;

use Engine\Db\AbstractModel;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Core\Model\Slug;

/**
 * Product Category Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_product_category');
 * @HasOne('id', '\Core\Model\Slug', 'objectid', {'alias': 'seo'})
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 * @Behavior('\Engine\Behavior\Model\NestedSet', {
 * 	'tablePrefix': 'pc_'
 * });
 */
class Pcategory extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="pc_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="pc_name")
    */
    public $name;

    /**
    * @Column(type="integer", nullable=true, column="pc_root")
    */
    public $root;

    /**
    * @Column(type="integer", nullable=true, column="pc_lft")
    */
    public $lft;

    /**
    * @Column(type="integer", nullable=true, column="pc_rgt")
    */
    public $rgt;

    /**
    * @Column(type="integer", nullable=true, column="pc_level")
    */
    public $level;

    /**
    * @Column(type="integer", nullable=true, column="pc_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="pc_count")
    */
    public $count;

    /**
    * @Column(type="string", nullable=true, column="pc_seo_description")
    */
    public $seodescription;

    /**
    * @Column(type="string", nullable=true, column="pc_seo_keyword")
    */
    public $seokeyword;

    /**
    * @Column(type="integer", nullable=true, column="pc_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="pc_datemodified")
    */
    public $datemodified;

    public $child;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

    public function getSeo()
    {
        return $this->getRelated('seo', [
            'conditions' => 'model = "' . Slug::MODEL_PCATEGORY . '"'
        ]);
    }

    /**
     * Form field validation
     */
    public function validation()
    {
        $this->validate(new PresenceOf(
            [
                'field'  => 'name',
                'message' => 'message-name-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'status',
                'message' => 'message-status-notempty'
            ]
        ));

        return $this->validationHasFailed() != true;
    }

    public function getStatusName()
    {
        $name = '';

        switch ($this->status) {
            case self::STATUS_ENABLE:
                $name = 'label-status-enable';
                break;
            case self::STATUS_DISABLE:
                $name = 'label-status-disable';
                break;
        }

        return $name;
    }

    public static function getStatusList()
    {
        return $data = [
            [
                "name" => 'label-status-enable',
                "value" => self::STATUS_ENABLE
            ],
            [
                "name" => 'label-status-disable',
                "value" => self::STATUS_DISABLE
            ],
        ];
    }

    public static function getStatus($status)
    {
        switch ($status) {
            case self::STATUS_ENABLE:
                $name = 'label-status-enable';
                break;
            case self::STATUS_DISABLE:
                $name = 'label-status-disable';
                break;
        }

        return $name;
    }

    public static function getStatusListArray()
    {
        return [
            self::STATUS_ENABLE,
            self::STATUS_DISABLE,
        ];
    }

    /**
     * Get label style for status
     */
    public function getStatusStyle()
    {
        $class = '';
        switch ($this->status) {
            case self::STATUS_ENABLE:
                $class = 'label label-info';
                break;
            case self::STATUS_DISABLE:
                $class = 'label label-important';
                break;
        }

        return $class;
    }
}
