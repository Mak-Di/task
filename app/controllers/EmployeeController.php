<?php

class EmployeeController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        //TODO Tree view implementation put here 
    }

    public function addAction()
    {
        $this->view->setVar('form', new Employee());

        if ($this->request->isPost()) {
            $employee = new Employees();
            $employee->firstName = $this->request->getPost('first_name', 'string');
            $employee->lastName = $this->request->getPost('last_name', 'string');
            $employee->position = $this->request->getPost('position', 'string');
            $employee->email = $this->request->getPost('email', 'email');
            $employee->phone = $this->request->getPost('phone', 'int');
            $employee->note = $this->request->getPost('note');
            $employee->parentId = $this->request->getPost('parent_id', 'int');

            if ($employee->save()) {
                $this->response->redirect('employee');
            } else {
                $this->view->setVar('errorMsg', $employee->getMessages());
            }
        }
    }
}
