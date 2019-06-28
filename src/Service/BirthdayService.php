<?php

namespace App\Service;

use App\Entity\Birthday;
use App\Repository\BirthdayRepository;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * BirthdayService
 *
 * @category Service
 * @package  App\Service
 * @author   Temuri Takalandze <takalandzet@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     null
 */
class BirthdayService
{
    private $_em;

    private $_mailer;

    private $_birthdayRepository;

    private $_emailRepository;

    /**
     * BirthdayService constructor.
     *
     * @param EntityManagerInterface $em Doctrine Entity Manager.
     * @param MailerService $mailer Mailer Service.
     * @param BirthdayRepository $birthdayRepository Birthday entity repository.
     * @param EmailRepository $emailRepository Email entity repository.
     */
    public function __construct(
        EntityManagerInterface $em,
        MailerService $mailer,
        BirthdayRepository $birthdayRepository,
        EmailRepository $emailRepository
    )
    {
        $this->_em = $em;
        $this->_mailer = $mailer;
        $this->_birthdayRepository = $birthdayRepository;
        $this->_emailRepository = $emailRepository;
    }

    /**
     * Get birthdays by date.
     *
     * @param \DateTime $date Birthday.
     *
     * @return array|null Birthdays.
     */
    public function getBirthdays(\DateTime $date): ?array
    {
        $birthdays = $this->_birthdayRepository
            ->findByBirthday($date);

        return $birthdays;
    }

    /**
     * Get emails for sending notifications.
     *
     * @return array|null
     */
    public function getEmails(): ?array
    {
        $emails = $this->_emailRepository
            ->getEmails();

        return $emails;
    }

    /**
     * Send notification about birthdays.
     *
     * @param \DateTime $date Birthday date.
     *
     * @return bool
     */
    public function sendNotification(\DateTime $date)
    {
        // Get birthdays.
        $birthdays = $this->getBirthdays($date);

        $sendingStatus = false;
        if (!empty($birthdays)) {
            $birthdayNames = [];
            foreach ($birthdays as $birthday) {
                /**
                 * Birthday Entity type.
                 *
                 * @var Birthday $birthday
                 */
                $birthdayNames[] = $birthday->getName();
            }

            try {
                // Get emails for sending notifications.
                $emails = $this->getEmails();

                $sendingStatus = $this->_mailer
                    ->sendMessage(
                        $emails,
                        'New Birthdays',
                        'Emails/birthday.html.twig',
                        [
                            'date' => $date,
                            'names' => $birthdayNames
                        ]
                    );
            } catch (LoaderError $e) {
            } catch (RuntimeError $e) {
            } catch (SyntaxError $e) {
            }
        }

        return $sendingStatus;
    }
}
