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
        $this->assets
            ->addJs('//code.jquery.com/ui/1.11.4/jquery-ui.js')
            ->addJs('js/autocomplete.js')
            ->addCss('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');

        $employeeId = $this->request->getQuery('eid', 'int', 0);
        $paginator   = new PaginatorModel(
            array(
                "builder"  => Employees::findWithChiefName($employeeId),
                "limit" => 5,
                "page"  => $page
            )
        );

        $searchElement = new Autocomplete('search', ['placeholder' => 'Search by full name']);
        $this->view->setVar('paginator', $paginator->getPaginate());
        $this->view->setVar('search', $searchElement);
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

    /**
     * Remove employee entity without confirmation
     * 
     * @param int $employeeId
     * @param int $page
     */
    public function removeAction($employeeId = 0, $page = 0)
    {
        $employee = Employees::findFirst($employeeId);
        if ($employee)
            $employee->delete();

        $this->response->redirect('employee/show/' . $page);
    }

    /**
     * Return search result by full employee name in json format
     * 
     * @return \Phalcon\Http\Response
     */
    public function searchAction()
    {
        $this->view->disable();
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $searchName = $this->request->getQuery('name');
        $result = [];
        foreach (Employees::searchByFullName($searchName) as $employee) {
            $result[] = [
                'value' => $employee->id,
                'label' => $employee->fullName
            ];
        }
        $response->setContent(json_encode($result));

        return $response;
    }
}
