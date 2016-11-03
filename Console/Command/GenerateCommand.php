<?php

namespace GoMage\Feed\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State as AppState;

class GenerateCommand extends Command
{

    const INPUT_KEY_FEED_ID = 'feed_id';

    /**
     * @var AppState
     */
    protected $_appState;

    /**
     * @var  \GoMage\Feed\Model\Generator
     */
    protected $_generator;


    public function __construct(
        AppState $appState,
        \GoMage\Feed\Model\Generator $generator
    ) {
        $this->_appState  = $appState;
        $this->_generator = $generator;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('gomage:feed:generate');
        $this->setDescription('Generate Feed');

        $this->addArgument(
            self::INPUT_KEY_FEED_ID,
            InputArgument::REQUIRED,
            'Feed ID'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_appState->setAreaCode('catalog');
        $feedId = $input->getArgument(self::INPUT_KEY_FEED_ID);

        try {
            $this->_generator->generate($feedId);
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        $output->writeln("<info>Feed has been successfully generated.</info>");
    }

}
