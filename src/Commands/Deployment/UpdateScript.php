<?php

namespace Sven\ForgeCLI\Commands\Deployment;

use Sven\ForgeCLI\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateScript extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('site:update-deploy-script')
            ->addArgument('server', InputArgument::REQUIRED, 'The id of the server the site is on.')
            ->addArgument('site', InputArgument::REQUIRED, 'The id of the site you want to update the deployment script of.')
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'The file your new deployment script is in.')
            ->setDescription('Update the deployment script of the given site.');
    }

    /**
     * {@inheritdoc}
     */
    public function perform(InputInterface $input, OutputInterface $output)
    {
        $this->forge->updateSiteDeploymentScript(
            $input->getArgument('server'), $input->getArgument('site'), $this->getFileContent($input)
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return bool|string
     */
    protected function getFileContent(InputInterface $input)
    {
        $filename = $input->hasArgument('file') ? $input->getArgument('file') : 'php://stdin';

        if ($filename && ftell(STDIN) === 0) {
            return file_get_contents($filename);
        }

        throw new \InvalidArgumentException('This command requires either the "--file" option to be set or an input from STDIN.');
    }
}
