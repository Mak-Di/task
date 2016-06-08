<?php

use Phalcon\Escaper;

class EmployeeController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $this->assets
            ->addJs('js/jquery.easytree.min.js')
            ->addJs('js/treeview.js')
            ->addCss('css/ui.easytree.css');
    }

    public function treeviewAction($parentId = 0)
    {
        $employeeArray = [];
        $this->view->disable();
        $response = new \Phalcon\Http\Response();
        $escaper = new Escaper();
        $response->setContentType('application/json', 'UTF-8');

        /**
         * @var $employee Employees
         */
        foreach (Employees::getChildren($parentId) as $employee) {
            $employeeArray[] = [
                'isActive' => false,
                'isFolder' => $employee->isChief(),
                'isExpanded' => $employee->isChief(),
                'isLazy' => true,
                'text' => $escaper->escapeHtml($employee->firstName.' ' . $employee->lastName),
                'lazyUrl' => '/employee/treeview/' . $employee->id
            ];
        }

        $response->setContent(json_encode($employeeArray));
        return $response;
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
