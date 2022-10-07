<?php

namespace TemilliamSoftware\RestrictRoleByIp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Reset extends Command {

    protected $roleCollectionFactory;

    public function __construct(
        \Magento\Authorization\Model\ResourceModel\Role\CollectionFactory $roleCollectionFactory,
        string $name = NULL
    ) {
        $this->roleCollectionFactory = $roleCollectionFactory;
        parent::__construct($name);
    }

    public function getRoles() {
        return $this->roleCollectionFactory->create();
    }

    protected function configure() {
        $this->setName('restrictrolebyip:reset');
        $this->setDescription('Removes all IP restrictions from all roles. Useful if you have locked yourself out of the web-based backend.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Create an empty JSON-encoded array.
        $ipData = json_encode([]);
        // Get all of the roles in the system.
        $roles = $this->getRoles();
        // Iterate over the roles.
        foreach ($roles as $role) {
            // Set the role's Restrict Role by IP settings to an empty array, hence removing restrictions.
            $role->setIpAddresses($ipData);
            $role->save();
        }
        $output->writeln("IP restrictions have been removed for all roles.");
    }
}