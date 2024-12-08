<?php
namespace Theme\Helpers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use const SMTP_DEBUG;
use const SMTP_FROM;
use const SMTP_FROM_NAME;
use const SMTP_HOST;
use const SMTP_PASSWORD;
use const SMTP_PORT;
use const SMTP_USERNAME;

class SmtpHelper {

    /**
     * Initialize the SMTP configuration
     */
    public static function init(): void
    {
        if (!defined('ABSPATH')) {
            die();
        }

        add_action('phpmailer_init', [self::class, 'configureSMTP']);
    }

    /**
     * Configure the SMTP settings
     *
     * @param PHPMailer $phpmailer The PHPMailer instance
     * @throws Exception
     */
    public static function configureSMTP(PHPMailer $phpmailer): void
    {
        // SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = SMTP_HOST;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = SMTP_PORT;
        $phpmailer->Username = SMTP_USERNAME;
        $phpmailer->Password = SMTP_PASSWORD;
        $phpmailer->SMTPSecure = 'tls';

        // From
        $phpmailer->From = SMTP_FROM;
        $phpmailer->FromName = SMTP_FROM_NAME;
        $phpmailer->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

        // Debug
        if (SMTP_DEBUG != 0) {
            $phpmailer->SMTPDebug  = SMTP_DEBUG;
            $phpmailer->Debugoutput = function ($str, $level) {
                echo "PHPMailer debug level $level: $str\n";
            };
        }
    }
}
