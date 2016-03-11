<?php
namespace Article\Model;

use Engine\Db\AbstractModel;
use Phalcon\Mvc\Model\Validator\PresenceOf;

/**
 * Article Language Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('ph_article_lang');
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class ArticleLang extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="al_id")
    */
    public $id;

    /**
    * @Column(type="integer", nullable=true, column="a_id")
    */
    public $aid;

    /**
    * @Column(type="integer", nullable=true, column="u_id")
    */
    public $uid;

    /**
    * @Column(type="integer", nullable=true, column="l_code")
    */
    public $lcode;

    /**
    * @Column(type="string", nullable=true, column="al_title")
    */
    public $title;

    /**
    * @Column(type="string", nullable=true, column="al_content")
    */
    public $content;

    /**
    * @Column(type="string", nullable=true, column="al_seo_keyword")
    */
    public $seoKeyword;

    /**
    * @Column(type="string", nullable=true, column="al_seo_description")
    */
    public $seoDescription;

    /**
    * @Column(type="integer", nullable=true, column="al_revision")
    */
    public $revision;

    /**
    * @Column(type="integer", nullable=true, column="al_author_type")
    */
    public $authorType;

    /**
    * @Column(type="integer", nullable=true, column="al_status")
    */
    public $status;

    /**
    * @Column(type="string", nullable=true, column="al_reject_reason")
    */
    public $rejectReason;

    const TYPE_CREATOR = 1;
    const TYPE_CONTRIBUTOR = 3;
    const STATUS_APPROVE = 1;
    const STATUS_REJECT = 3;
    const STATUS_PENDING = 5;

    public function initialize()
    {

    }

    // Overwrite related magic method to get category information
    // based on language code from session
    public function getLang()
    {

    }

}
