<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Filter;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

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
    public $parentId;

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

    /**
     * @param int $employeeId
     * @return Builder
     */
    public static function findWithChiefName($employeeId = 0)
    {
        $queryBuilder = new Builder();
        $queryBuilder
            ->from('Employees')
            ->columns([
                'Employees.id',
                'Employees.firstName',
                'Employees.lastName',
                'Employees.position',
                'Employees.email',
                'Employees.phone',
                'Employees.note',
                'chiefName' => 'CONCAT(e.firstName, " ", e.lastName)'
            ])
            ->leftJoin('Employees', 'Employees.parentId = e.id', 'e');

        if ($employeeId > 0) {
            $queryBuilder->where('Employees.id = :id:', ['id' => $employeeId]);
        }

        return $queryBuilder;
    }

    /**
     * Set Chief flag if need it
     */
    public function afterCreate()
    {
        $parent = Employees::findFirst($this->parentId);
        $parent->isChief = 1;
        $parent->save();
    }

    /**
     * Set / un set chief flag before update
     */
    public function beforeUpdate()
    {
        $fromDB = Employees::findFirst($this->id);
        if ($fromDB->parentId != $this->parentId) {
            $children = Employees::getChildren($fromDB->parentId);
            $parent = Employees::findFirst($fromDB->parentId);
            if ($parent) {
                if (count($children) == 1) {
                    $parent->isChief = 0;
                    $parent->save();
                }
            } else {
                $parent = Employees::findFirst($this->parentId);
                $parent->isChief = 1;
                $parent->save();
            }

        }
    }

    /**
     * Search by full name
     * 
     * @param $query
     * @return Resultset
     */
    public static function searchByFullName($query)
    {
        $filter = new Filter();
        $employees = new Employees();
        $query = $filter->sanitize($query, 'alphanum');
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as fullName 
                FROM employees 
                WHERE MATCH(first_name, last_name) AGAINST ( ? IN BOOLEAN MODE)";

        return new Resultset(null, $employees, $employees->getReadConnection()->query($sql, [$query . '*']));
    }

}
