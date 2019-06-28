<?php

namespace App\Command;

use App\Service\BirthdayService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * BirthdayNotifyCommand
 *
 * @category Command
 * @package  App\Command
 * @author   Temuri Takalandze <takalandzet@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     null
 */
class BirthdayNotifyCommand extends Command
{
    protected static $defaultName = 'birthday:notify';

    private $_birthdayService;

    /**
     * BirthdayNotifyCommand constructor.
     *
     * @param BirthdayService $birthdayService Birthday service.
     */
    public function __construct(BirthdayService $birthdayService)
    {
        $this->_birthdayService = $birthdayService;

        parent::__construct();
    }

    /**
     * Configure command.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function configure()
    {
        $currentDate = new \DateTime();

        $this
            ->setDescription('Send birthday notifications')
            ->addOption(
                'day',
                null,
                InputOption::VALUE_OPTIONAL,
                'Birthday Day',
                $currentDate->format('d')
            )
            ->addOption(
                'month',
                null,
                InputOption::VALUE_OPTIONAL,
                'Birthday Month',
                $currentDate->format('m')
            );
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input  Input interface.
     * @param OutputInterface $output Output interface
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $day = (int)$input->getOption('day');
        $month = (int)$input->getOption('month');

        $sendingStatus = false;

        if (0 > $day || 31 < $day) {
            $io->error('Invalid Day range!');
        } else if (0 > $month || 12 < $month) {
            $io->error('Invalid Month range!');
        } else {
            $birthday = new \DateTime();
            $birthday->setDate($birthday->format('Y'), $month, $day);

            $sendingStatus = $this->_birthdayService->sendNotification($birthday);
        }

        if ($sendingStatus) {
            $io->success('Emails has been delivered successfully!');
        }
    }
}
