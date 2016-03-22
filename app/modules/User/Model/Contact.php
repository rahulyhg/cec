<?php
namespace User\Model;

use Engine\Db\AbstractModel;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Email;

/**
 * Contact Model.
 *
 * @category  ThePhalconPHP
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 *
 * @Source('cec_contact');
 * @Behavior('\Engine\Behavior\Model\Timestampable');
 */
class Contact extends AbstractModel
{
    /**
    * @Primary
    * @Identity
    * @Column(type="integer", nullable=false, column="co_id")
    */
    public $id;

    /**
    * @Column(type="string", nullable=true, column="co_fullname")
    */
    public $fullname;

    /**
    * @Column(type="string", nullable=true, column="co_address")
    */
    public $address;

    /**
    * @Column(type="string", nullable=true, column="co_company")
    */
    public $company;

    /**
    * @Column(type="string", nullable=true, column="co_phone")
    */
    public $phone;

    /**
    * @Column(type="string", nullable=true, column="co_email")
    */
    public $email;

    /**
    * @Column(type="string", nullable=true, column="co_content")
    */
    public $content;

    /**
    * @Column(type="integer", nullable=true, column="co_datecreated")
    */
    public $datecreated;

    /**
    * @Column(type="integer", nullable=true, column="co_datemodified")
    */
    public $datemodified;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 3;

    /**
     * Form field validation
     */
    public function validation()
    {
        $this->validate(new PresenceOf(
            [
                'field'  => 'fullname',
                'message' => 'message-fullname-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'email',
                'message' => 'message-email-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'company',
                'message' => 'message-company-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'phone',
                'message' => 'message-phone-notempty'
            ]
        ));

        $this->validate(new PresenceOf(
            [
                'field'  => 'content',
                'message' => 'message-content-notempty'
            ]
        ));

        $this->validate(new Email(
            [
                'field'  => 'email',
                'message' => 'message-email-invalid'
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
}
