<?php
namespace Article\Model;

use Engine\Db\AbstractModel;
use Engine\Behavior\Model\Imageable;
use Phalcon\Mvc\Model\Validator\PresenceOf;

/**
 * Article Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_article');
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class Article extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="a_id")
    */
    public $id;

    /**
    * @Column(type="integer", nullable=true, column="c_id")
    */
    public $cid;

    /**
    * @Column(type="integer", nullable=true, column="u_id")
    */
    public $uid;

    /**
    * @Column(type="string", nullable=true, column="a_slug")
    */
    public $slug;

    /**
    * @Column(type="integer", nullable=true, column="a_display_order")
    */
    public $displayorder;

    /**
    * @Column(type="integer", nullable=true, column="a_display_to_home")
    */
    public $displaytohome;

    /**
    * @Column(type="string", nullable=true, column="a_content")
    */
    public $content;

    /**
    * @Column(type="string", nullable=true, column="a_image")
    */
    public $image;

    /**
    * @Column(type="string", nullable=true, column="a_seo_description")
    */
    public $seodescription;

    /**
    * @Column(type="string", nullable=true, column="a_seo_keyword")
    */
    public $seokeyword;

    /**
    * @Column(type="integer", nullable=true, column="a_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="a_type")
    */
    public $type;

    /**
    * @Column(type="integer", nullable=true, column="a_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="a_datemodified")
    */
    public $datemodified;

    const STATUS_DISABLE = 3;
    const STATUS_ENABLE = 1;

    public function initialize()
    {

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

    public static function getStatusListArray()
    {
        return [
            self::STATUS_ENABLE,
            self::STATUS_DISABLE,

        ];
    }
}
