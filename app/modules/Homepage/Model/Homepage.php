<?php
namespace Homepage\Model;

use Engine\Db\AbstractModel;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Engine\Behavior\Model\Imageable;

/**
 * Homepage Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_homepage');
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class Homepage extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="hp_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="hp_title")
    */
    public $title;

    /**
    * @Column(type="string", nullable=true, column="hp_summary")
    */
    public $summary;

    /**
    * @Column(type="string", nullable=true, column="hp_seo_description")
    */
    public $seodescription;

    /**
    * @Column(type="string", nullable=true, column="hp_seo_keyword")
    */
    public $seokeyword;

    /**
    * @Column(type="string", nullable=false, column="hp_image")
    */
    public $image;

    /**
    * @Column(type="string", nullable=false, column="hp_url")
    */
    public $url;

    /**
    * @Column(type="integer", nullable=true, column="hp_display_order")
    */
    public $displayorder;

    /**
    * @Column(type="integer", nullable=true, column="hp_type")
    */
    public $type;

    /**
    * @Column(type="integer", nullable=false, column="hp_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="hp_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="hp_datemodified")
    */
    public $datemodified;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

    /**
     * Initialize model
     */
    public function initialize()
    {
        $config = $this->getDI()->get('config');

        $this->image = $config->global->homepage->directory . date('Y') . '/' . date('m');
        $this->addBehavior(new Imageable([
            'uploadPath' => $this->image,
            'sanitize' => $config->global->homepage->sanitize,
            'allowedFormats' => $config->global->homepage->mimes->toArray(),
            'allowedMinimumSize' => $config->global->homepage->minsize,
            'allowedMaximunSize' => $config->global->homepage->maxsize
        ]));
    }

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
                'field'  => 'image',
                'message' => 'message-image-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'url',
                'message' => 'message-url-notempty'
            ]
        ));

        return $this->validationHasFailed() != true;
    }

    /**
     * Create Paginator Object
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
}
