<?php

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Email,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\TextArea,
    Phalcon\Forms\Element\Select;

class Employee extends Form
{
    public function initialize()
    {
        $firstName = new Text('firstName', ['placeholder' => 'First name', 'required' => true]);
        $this->add($firstName);

        $lastName = new Text('lastName', ['placeholder' => 'Last name', 'required' => true]);
        $this->add($lastName);

        $position = new Text('position', ['placeholder' => 'Position']);
        $this->add($position);

        $email = new Email('email', ['placeholder' => 'E-mail', 'required' => true]);
        $this->add($email);

        $phone = new Text('phone', ['placeholder' => 'Phone number']);
        $this->add($phone);

        $note = new TextArea('note', ['placeholder' => 'Note']);
        $this->add($note);

        // TODO Autocomplete implementation put here
        $parentId = new Select(

            'parentId',
            Employees::find(['columns' => "id, CONCAT(firstName, ' ', lastName) as fullName"]),
            [
                'using' => ['id', 'fullName'],
                'useEmpty' => true,
                'emptyValue' => 0
            ]
            );
        $this->add($parentId);
    }
}
