<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Employees extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $firstName;

    /**
     *
     * @var string
     */
    public $lastName;

    /**
     *
     * @var string
     */
    public $position;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var integer
     */
    public $isChief;

    /**
     *
     * @var integer
     */
    public $parenId;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'employees';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Employees[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Employees
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'position' => 'position',
            'email' => 'email',
            'phone' => 'phone',
            'note' => 'note',
            'is_chief' => 'isChief',
            'parent_id' => 'parentId'
        );
    }

    /**
     * Get children
     *
     * @param int $parentId
     * @return mixed
     */
    public static function getChildren($parentId = 0)
    {
        return parent::findByParentId($parentId);
    }

    /**
     * @return bool
     */
    public function isChief()
    {
        return (bool)$this->isChief;
    }
}
