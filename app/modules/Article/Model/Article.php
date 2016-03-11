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
 * @Source('ph_article');
 * @HasMany('id', '\Article\Model\ArticleLang', 'aid', {'alias': 'lang'})
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class Category extends AbstractModel
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
    * @Column(type="integer", nullable=true, column="a_order_no")
    */
    public $orderNo;

    /**
    * @Column(type="string", nullable=true, column="a_identifier")
    */
    public $identifier;

    /**
    * @Column(type="integer", nullable=true, column="a_count_comment")
    */
    public $countComment;

    /**
    * @Column(type="integer", nullable=true, column="a_count_view")
    */
    public $countView;

    /**
    * @Column(type="integer", nullable=true, column="a_allow_comment")
    */
    public $allowComment;

    /**
    * @Column(type="integer", nullable=true, column="a_ip_address")
    */
    public $ipAddress;

    /**
    * @Column(type="string", nullable=true, column="a_contributor_list")
    */
    public $contributorList;

    /**
    * @Column(type="integer", nullable=true, column="a_datepublish")
    */
    public $datepublish;

    /**
    * @Column(type="integer", nullable=true, column="a_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="a_datemodified")
    */
    public $datemodified;

    protected $lang;

    const IS_ALLOW_COMMENT = 1;
    const IS_NOT_ALLOW_COMMENT = 0;
    const STATUS_DISABLE = 3;
    const STATUS_PUBLISH = 1;
    const STATUS_DRAFT = 5;

    public function initialize()
    {

    }

    // Overwrite related magic method to get category information
    // based on language code from session
    public function getLang()
    {
        return $this->getRelated('lang', [
            'lcode = :languageCode:',
            'bind' => [
                'languageCode' => $this->getDI()->get('session')->get('languageCode')
            ]
        ]);
    }

}
