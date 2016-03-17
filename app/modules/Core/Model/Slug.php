<?php
namespace Core\Model;

use Engine\Db\AbstractModel;
use Engine\Behavior\Model\Imageable;
use Phalcon\Mvc\Model\Validator\PresenceOf;

/**
 * Slug Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_slug');
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class Slug extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="s_id")
    */
    public $id;

    /**
    * @Column(type="integer", nullable=true, column="u_id")
    */
    public $uid;

    /**
    * @Column(type="string", nullable=true, column="s_slug")
    */
    public $slug;

    /**
    * @Column(type="integer", nullable=true, column="s_objectid")
    */
    public $objectid;

    /**
    * @Column(type="string", nullable=true, column="s_hash")
    */
    public $hash;

    /**
    * @Column(type="string", nullable=true, column="s_model")
    */
    public $model;

    /**
    * @Column(type="integer", nullable=true, column="s_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="s_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="s_datemodified")
    */
    public $datemodified;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

    const MODEL_ARTICLE = "Article";
    const MODEL_PRODUCT = "Product";
    const MODEL_CATEGORY = "Category";
    const MODEL_PCATEGORY = "Pcategory";

    public function beforeCreate()
    {
        // Change slug when duplicate
        $mySlug = Slug::findFirst([
            'hash = :hash:',
            'bind' => ['hash' => $this->hash]
        ]);

        if ($mySlug) {
            $this->slug = $this->slug . '-' . time();
            $this->hash = md5($this->slug);
        }
    }

    /**
     * Form field validation
     */
    public function validation()
    {
        $this->validate(new PresenceOf(
            [
                'field'  => 'slug',
                'message' => 'message-slug-notempty'
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

    public function getModelName()
    {
        $name = '';

        switch ($this->model) {
            case self::MODEL_PRODUCT:
                $name = 'label-model-product';
                break;
            case self::MODEL_ARTICLE:
                $name = 'label-model-article';
                break;
            case self::MODEL_PCATEGORY:
                $name = 'label-model-product-category';
                break;
            case self::MODEL_CATEGORY:
                $name = 'label-model-article-category';
                break;
        }

        return $name;
    }

    public function getObjectName()
    {
        $name = "";
        $model = '\\' . $this->model . '\\Model\\' . $this->model;

        $myObject = $model::findFirst($this->objectid);

        if ($this->model == self::MODEL_ARTICLE) {
            $name = $myObject->title;
        } else {
            $name = $myObject->name;
        }

        return $name;
    }
}
