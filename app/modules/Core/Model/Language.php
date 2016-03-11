<?php
namespace Core\Model;

use Engine\Db\AbstractModel;

/**
 * Language Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('ph_language');
 */
class Language extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="l_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="l_code")
    */
    public $code;

    /**
    * @Column(type="string", nullable=true, column="l_country_code")
    */
    public $countrycode;

    /**
    * @Column(type="string", nullable=true, column="l_name")
    */
    public $name;

    /**
    * @Column(type="integer", nullable=true, column="l_default")
    */
    public $default;

    /**
    * @Column(type="integer", nullable=true, column="l_order_no")
    */
    public $orderno;

    /**
    * @Column(type="integer", nullable=false, column="l_status")
    */
    public $status;

    /**
    * @Column(type="integer", nullable=true, column="l_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="l_datemodified")
    */
    public $datemodified;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;
    const IS_DEFAULT = 1;
    const IS_NOT_DEFAULT = 0;

    /**
     * Initialize model
     */
    public function initialize()
    {
        // $config = $this->getDI()->get('config');
        //
        // $this->avatar = $config->global->user->directory . date('Y') . '/' . date('m');
        // $this->addBehavior(new Imageable([
        //     'uploadPath' => $this->avatar,
        //     'sanitize' => $config->global->user->sanitize,
        //     'allowedFormats' => $config->global->user->mimes->toArray(),
        //     'allowedMinimumSize' => $config->global->user->minsize,
        //     'allowedMaximunSize' => $config->global->user->maxsize
        // ]));
    }

    /**
     * Form field validation
     */
    // public function validation()
    // {
    //     $this->validate(new PresenceOf(
    //         [
    //             'field'  => 'name',
    //             'message' => 'message-name-notempty'
    //         ]
    //     ));
    //
    //     return $this->validationHasFailed() != true;
    // }

    /**
     * Create Paginator Object for Language Listing
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
     * Get thumbnail avatar image
     * @return [string] Images thumb url.
     */
    public function getThumbnailImage()
    {
        $pos = strrpos($this->avatar, '.');
        $extPart = substr($this->avatar, $pos+1) != '' ? substr($this->avatar, $pos+1) : 'jpeg';
        $namePart =  substr($this->avatar,0, $pos);
        $file = $namePart . '-thumb.' . $extPart;

        return $file;
    }

    /**
     * Get medium avatar image
     * @return [string] Images medium url.
     */
    public function getMediumImage()
    {
        $pos = strrpos($this->avatar, '.');
        $extPart = substr($this->avatar, $pos+1) != '' ? substr($this->avatar, $pos+1) : 'jpeg';
        $namePart =  substr($this->avatar,0, $pos);
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
}
