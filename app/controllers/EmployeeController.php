<?php

use Phalcon\Escaper;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorModel;

class EmployeeController extends \Phalcon\Mvc\Controller
{
    /**
     * Draw treeview
     */
    public function indexAction()
    {
        $this->assets
            ->addJs('js/jquery.easytree.min.js')
            ->addJs('js/treeview.js')
            ->addCss('css/ui.easytree.css');
    }

    /**
     * Return first level children of parent employee (chief)
     * in json format.
     *
     * @param int $parentId
     * @return \Phalcon\Http\Response
     */
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

    /**
     * Add new employee form page
     */
    public function addAction()
    {
        $this->view->setVar('form', new Employee());

        if ($this->request->isPost()) {
            $employee = new Employees();
            $employee->firstName = $this->request->getPost('firstName', 'string');
            $employee->lastName = $this->request->getPost('lastName', 'string');
            $employee->position = $this->request->getPost('position', 'string');
            $employee->email = $this->request->getPost('email', 'email');
            $employee->phone = $this->request->getPost('phone', 'int');
            $employee->note = $this->request->getPost('note');
            $employee->parentId = $this->request->getPost('parentId', 'int');

            if ($employee->save()) {
                $this->response->redirect('employee');
            } else {
                $this->view->setVar('errorMsg', $employee->getMessages());
            }
        }
    }

    /**
     * Show all details of employees list and links to edit page
     * 
     * @param int $page
     */
    public function showAction($page = 0)
    {
        $paginator   = new PaginatorModel(
            array(
                "builder"  => Employees::findWithChiefName(),
                "limit" => 5,
                "page"  => $page
            )
        );

        $this->view->setVar('paginator', $paginator->getPaginate());
    }

    /**
     * Edit employee form page
     *
     * @param int $employeeId
     * @param int $page
     */
    public function editAction($employeeId = 0, $page = 0)
    {
        $this->view->setVar('form', new Employee(Employees::findFirst($employeeId)));
        $this->view->setVar('id', $employeeId);
        $this->view->setVar('page', $page);

        if ($this->request->isPost()) {
            $employee = Employees::findFirst($employeeId);
            $employee->firstName = $this->request->getPost('firstName', 'string');
            $employee->lastName = $this->request->getPost('lastName', 'string');
            $employee->position = $this->request->getPost('position', 'string');
            $employee->email = $this->request->getPost('email', 'email');
            $employee->phone = $this->request->getPost('phone', 'int');
            $employee->note = $this->request->getPost('note');
            $employee->parentId = $this->request->getPost('parentId', 'int');

            if ($employee->save()) {
                $this->response->redirect('employee/show/' . $page);
            } else {
                $this->view->setVar('errorMsg', $employee->getMessages());
            }
        }        
    }
}
