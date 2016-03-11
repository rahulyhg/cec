<?php
namespace Category\Model;

use Engine\Db\AbstractModel;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Core\Model\Language;

/**
 * Category Language Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('ph_category_lang');
 */
class CategoryLang extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="cl_id")
    */
    public $id;

    /**
    * @Column(type="integer", nullable=true, column="c_id")
    */
    public $cid;

    /**
    * @Column(type="string", nullable=true, column="l_code")
    */
    public $lcode;

    /**
    * @Column(type="string", nullable=true, column="cl_name")
    */
    public $name;

    /**
    * @Column(type="string", nullable=true, column="cl_description")
    */
    public $description;

    /**
    * @Column(type="string", nullable=true, column="cl_seo_keyword")
    */
    public $seokeyword;

    /**
    * @Column(type="string", nullable=true, column="cl_seo_description")
    */
    public $seodescription;

    public function validation()
    {
        $this->validate(new PresenceOf(
            [
                'field'  => 'name',
                'message' => 'message-name-notempty'
            ]
        ));

        return $this->validationHasFailed() != true;
    }

    // Return country on language code
    public function getCountryCode()
    {
        return Language::findFirstByCode($this->lcode)->countrycode;
    }
}
