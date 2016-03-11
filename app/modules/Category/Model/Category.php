<?php
namespace Category\Model;

use Engine\Db\AbstractModel;
use Engine\Behavior\Model\Imageable;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Category\Model\CategoryLang;

/**
 * Category Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_category');
 * @HasMany('id', '\Category\Model\CategoryLang', 'cid', {'alias': 'lang'})
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 * @Behavior('\Engine\Behavior\Model\NestedSet', {
 * 	'tablePrefix': 'c_'
 * });
 */
class Category extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="c_id")
    */
    public $id;

    /**
    * @Column(type="integer", nullable=true, column="c_root")
    */
    public $root;

    /**
    * @Column(type="integer", nullable=true, column="c_lft")
    */
    public $lft;

    /**
    * @Column(type="integer", nullable=true, column="c_rgt")
    */
    public $rgt;

    /**
    * @Column(type="integer", nullable=true, column="c_level")
    */
    public $level;

    /**
    * @Column(type="integer", nullable=true, column="c_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="c_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="c_datemodified")
    */
    public $datemodified;

    protected $lang;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

    public function initialize()
    {

    }

    // Get related magic method to get category information
    // based on language code from session
    public function getOneLangBySession()
    {
        $resultLangPack = $this->getRelated('lang', [
            'lcode = :languageCode: AND cid = :categoryId:',
            'bind' => [
                'languageCode' => $this->getDI()->get('session')->get('languageCode'),
                'categoryId' => $this->id
            ]
        ]);

        if (count($resultLangPack) > 0) {
            // Select first record from array result set.
            return $resultLangPack[0];
        } else {
            // Return language pack based on default language from setting.
            return [
                CategoryLang::findFirstByLcode($this->getDI()->get('config')->global->defaultLanguage)
            ];
        }
    }

    // Get all languages from category
    public function getLangs()
    {
        return $this->getRelated('lang', [
            'order' => 'lcode ASC'
        ]);
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
     * Get thumbnail iconpath image
     * @return [string] Images thumb url.
     */
    public function getThumbnailImage()
    {
        $pos = strrpos($this->iconpath, '.');
        $extPart = substr($this->iconpath, $pos+1) != '' ? substr($this->iconpath, $pos+1) : 'jpeg';
        $namePart =  substr($this->iconpath,0, $pos);
        $file = $namePart . '-thumb.' . $extPart;

        return $file;
    }

    /**
     * Get medium iconpath image
     * @return [string] Images medium url.
     */
    public function getMediumImage()
    {
        $pos = strrpos($this->iconpath, '.');
        $extPart = substr($this->iconpath, $pos+1) != '' ? substr($this->iconpath, $pos+1) : 'jpeg';
        $namePart =  substr($this->iconpath,0, $pos);
        $file = $namePart . '-medium.' . $extPart;

        return $file;
    }
}
