<?php

namespace Theme\Helpers;

defined('ABSPATH') || die();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Theme\Contracts\Registerable;

class SmtpHelper implements Registerable
{
	public function register(): void
	{
		add_action('phpmailer_init', [$this, 'configureSMTP']);
	}

	/**
	 * @param PHPMailer $phpmailer
	 * @throws Exception
	 */
	public function configureSMTP(PHPMailer $phpmailer): void
	{
		$phpmailer->isSMTP();
		$phpmailer->Host       = SMTP_HOST;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = SMTP_PORT;
		$phpmailer->Username   = SMTP_USERNAME;
		$phpmailer->Password   = SMTP_PASSWORD;
		$phpmailer->SMTPSecure = 'tls';

		$phpmailer->From     = SMTP_FROM;
		$phpmailer->FromName = SMTP_FROM_NAME;
		$phpmailer->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

		if (defined('SMTP_DEBUG') && SMTP_DEBUG != 0) {
			$phpmailer->SMTPDebug   = SMTP_DEBUG;
			$phpmailer->Debugoutput = function (string $str, int $level): void {
				error_log("PHPMailer debug level $level: $str");
			};
		}
	}
}
