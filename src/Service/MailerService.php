<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

/**
 * MailerService
 *
 * @category Service
 * @package  App\Service
 * @author   Temuri Takalandze <takalandzet@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     null
 */
class MailerService
{
    private $_mailer;

    private $_em;

    private $_twig;

    /**
     * MailerService constructor.
     *
     * @param Swift_Mailer           $mailer Mailer service.
     * @param EntityManagerInterface $em     Doctrine Entity Manager.
     * @param Environment            $twig   Template engine.
     */
    public function __construct(
        Swift_Mailer $mailer,
        EntityManagerInterface $em,
        Environment $twig
    ) {
        $this->_mailer = $mailer;
        $this->_em = $em;
        $this->_twig = $twig;
    }

    /**
     * Send email.
     *
     * @param array       $to        Recipient email(s).
     * @param string      $subject   Email subject.
     * @param string      $template  Twig template name.
     * @param array|null  $variables Variables for template engine.
     * @param string|null $from      Receiver email.
     *
     * @return boolean Sending status.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMessage(
        array $to,
        string $subject,
        string $template,
        array $variables = [],
        string $from = null
    ): bool {
        $message = new Swift_Message($subject);

        $message->setFrom($from ?: getenv('SITE_MAIL'))
            ->setTo($to)
            ->setBody(
                $this->_twig->render($template, $variables),
                'text/html'
            );

        $status = $this->_mailer
            ->send($message);

        return $status;
    }
}
