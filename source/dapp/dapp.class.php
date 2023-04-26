<?php

class dapp extends Core_Module
{
    final public function set_cfg()
    {
        $this->inst_script = [
            'module'  => [
                'title' => 'dApp',
            ],
            'actions' => [
                ['action' => 'create_project', 'title' => 'Create Project'],
                ['action' => 'manage_projects', 'title' => 'Manage Projects'],
                ['action' => 'import', 'title' => 'Import'],
            ],
            'needed'  => array(),
        ];

        $this->inst_project = new Project_DApp_Project();
        $this->inst_address = new Project_DApp_Address();
    }

    public function import()
    {
        $this
            ->inst_project
            ->getList($this->out['project_list']);

        $address = [];

        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                if (($handle = fopen($_FILES['csv']['tmp_name'], "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        $address[] = ['address' => current($data)];
                    }

                    fclose($handle);
                }

                $this
                    ->inst_address
                    ->withAddress(array_column($address, 'address'))
                    ->getList($added_address);

                $address = array_udiff($address, $added_address, function ($a, $b) {
                    return strcmp($a['address'], $b['address']);
                });

                if (!empty($address)) {
                    $this
                        ->inst_address
                        ->setEntered($address)
                        ->setMass();

                    $this
                        ->inst_address
                        ->getEntered($address);

                    $added_address = array_merge($address, $added_address);
                }

                $this
                    ->inst_address
                    ->addToProject($_POST['project_id'], array_column($added_address, 'id'));
                $this->location();
            }
        }
    }

    public function create_project()
    {
        if (empty($_GET['pid'])) {
            $this->out['arrData']['signature'] = bin2hex(openssl_random_pseudo_bytes(30));
        } else {
            $this->inst_project
                ->withIds($_GET['pid'])
                ->onlyOne()
                ->getList($this->out['arrData']);

            $this->inst_address
                ->withProjectId($_GET['pid'])
                ->getList($this->out['arrAddress']);
        }

        if (!empty($_POST)) {
            if ($this->inst_project->setEntered($_POST)->set()) {
                $this->location(['action' => 'manage_projects']);
            }

            $this->inst_project->getEntered($this->out['arrErrors']);
        }
    }

    public function manage_projects()
    {
        $this
            ->inst_project
            ->withCountAccount()
            ->getList($this->out['arrList']);

        if (!empty($_GET['delete_id'])) {
            if ($this
                ->inst_project
                ->withIds($_GET['delete_id'])
                ->del()) {
                $this->location(['action' => 'manage_projects']);
            }
        }
    }
}
