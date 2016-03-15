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
 * @HasOne('id', '\Core\Model\Slug', 'objectid', {'alias': 'seo'})
 * @HasMany('id', '\Core\Model\Image', 'aid', {'alias': 'galleries'})
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
    * @Column(type="string", nullable=true, column="a_title")
    */
    public $title;

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
    const IS_HOME = 1;
    const IS_NOTHOME = 3;
    const TYPE_NORMAL = 1;
    const TYPE_ACTIVITY = 3;
    const TYPE_PROJECT = 5;
    const TYPE_PAGE = 7;

    /**
     * Form field validation
     */
    public function validation()
    {
        $this->validate(new PresenceOf(
            [
                'field'  => 'title',
                'message' => 'message-title-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'content',
                'message' => 'message-content-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'type',
                'message' => 'message-type-notempty'
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

    // Get model relation with conditions
    public function getGalleries()
    {
        return $this->getRelated('galleries', [
            'conditions' => 'status = ' . self::STATUS_ENABLE,
            'order' => 'id ASC'
        ]);
    }

    /**
     * Create Paginator Object for Article Listing
     *
     * @param  [array] $formData    Store condition, order, select column to prepare for query
     * @param  [int] $limit         Record per page
     * @param  [int] $offset        Current Page
     * @return [object] $paginator  Phalcon Paginator Builder Object
     */
    public static function getList($formData, $limit, $offset)
    {
        $modelName = get_class();
        $whereString = '';
        $bindParams = [];
        $bindTypeParams = [];

        if (is_array($formData['conditions'])) {
            if (isset($formData['conditions']['keyword'])
                && strlen($formData['conditions']['keyword']) > 0
                && isset($formData['conditions']['searchKeywordIn'])
                && count($formData['conditions']['searchKeywordIn']) > 0) {
                /**
                 * Search keyword
                 */
                $searchKeyword = $formData['conditions']['keyword'];
                $searchKeywordIn = $formData['conditions']['searchKeywordIn'];

                $whereString .= $whereString != '' ? ' OR ' : ' (';

                $sp = '';
                foreach ($searchKeywordIn as $searchIn) {
                    $sp .= ($sp != '' ? ' OR ' : '') . $searchIn . ' LIKE :searchKeyword:';
                }

                $whereString .= $sp . ')';
                $bindParams['searchKeyword'] = '%' . $searchKeyword . '%';
            }

            /**
             * Optional Filter by tags
             */
            if (count($formData['conditions']['filterBy']) > 0) {
                $filterby = $formData['conditions']['filterBy'];

                foreach ($filterby as $k => $v) {
                    if ($v) {
                        $whereString .= ($whereString != '' ? ' AND ' : '') . $k . ' = :' . $k . ':';
                        $bindParams[$k] = $v;

                        switch (gettype($v)) {
                            case 'string':
                                $bindTypeParams[$k] =  \PDO::PARAM_STR;
                                break;

                            default:
                                $bindTypeParams[$k] = \PDO::PARAM_INT;
                                break;
                        }
                    }
                }
            }

            if (strlen($whereString) > 0 && count($bindParams) > 0) {
                $formData['conditions'] = [
                    [
                        $whereString,
                        $bindParams,
                        $bindTypeParams
                    ]
                ];
            } else {
                $formData['conditions'] = '';
            }
        }

        $params = [
            'models' => $modelName,
            'columns' => $formData['columns'],
            'conditions' => $formData['conditions'],
            'order' => [$modelName . '.' . $formData['orderBy'] .' '. $formData['orderType'] .'']
        ];

        return parent::runPaginate($params, $limit, $offset);
    }

    /**
     * Get thumbnail image
     * @return [string] Images thumb url.
     */
    public function getThumbnailImage()
    {
        $pos = strrpos($this->image, '.');
        $extPart = substr($this->image, $pos+1) != '' ? substr($this->image, $pos+1) : 'jpeg';
        $namePart =  substr($this->image,0, $pos);
        $file = $namePart . '-thumb.' . $extPart;

        return $file;
    }

    /**
     * Get medium image
     * @return [string] Images medium url.
     */
    public function getMediumImage()
    {
        $pos = strrpos($this->image, '.');
        $extPart = substr($this->image, $pos+1) != '' ? substr($this->image, $pos+1) : 'jpeg';
        $namePart =  substr($this->image,0, $pos);
        $file = $namePart . '-medium.' . $extPart;

        return $file;
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

    public function getDisplayToHomeName()
    {
        $name = '';

        switch ($this->displaytohome) {
            case self::IS_NOTHOME:
                $name = 'label-ishome-disable';
                break;
            case self::IS_HOME:
                $name = 'label-ishome-enable';
                break;
        }

        return $name;
    }

    public static function getDisplayToHomeList()
    {
        return $data = [
            [
                "name" => 'label-ishome-disable',
                "value" => self::IS_NOTHOME
            ],
            [
                "name" => 'label-ishome-enable',
                "value" => self::IS_HOME
            ]
        ];
    }

    public static function getDisplayToHomeListArray()
    {
        return [
            self::IS_NOTHOME,
            self::IS_HOME
        ];
    }

    public function getTypeName()
    {
        $name = '';

        switch ($this->type) {
            case self::TYPE_NORMAL:
                $name = 'label-type-normal';
                break;
            case self::TYPE_PROJECT:
                $name = 'label-type-project';
                break;
            case self::TYPE_ACTIVITY:
                $name = 'label-type-activity';
                break;
            case self::TYPE_PAGE:
                $name = 'label-type-page';
                break;
        }

        return $name;
    }

    public static function getTypeList()
    {
        return $data = [
            [
                "name" => 'label-type-normal',
                "value" => self::TYPE_NORMAL
            ],
            [
                "name" => 'label-type-project',
                "value" => self::TYPE_PROJECT
            ],
            [
                "name" => 'label-type-activity',
                "value" => self::TYPE_ACTIVITY
            ],
            [
                "name" => 'label-type-page',
                "value" => self::TYPE_PAGE
            ]
        ];
    }

    public static function getTypeListArray()
    {
        return [
            self::TYPE_NORMAL,
            self::TYPE_PROJECT,
            self::TYPE_ACTIVITY,
            self::TYPE_PAGE
        ];
    }
}
