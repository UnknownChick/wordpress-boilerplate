<?php defined('ABSPATH') || die();

use function Env\env;

add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = env('SMTP_HOST');
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = env('SMTP_PORT');
    $phpmailer->Username = env('SMTP_USERNAME');
    $phpmailer->Password = env('SMTP_PASSWORD');
    $phpmailer->SMTPSecure = 'tls';

    $phpmailer->From = env('SMTP_FROM');
    $phpmailer->FromName = env('SMTP_FROM_NAME');
    $phpmailer->addReplyTo(env('SMTP_FROM'), env('SMTP_FROM_NAME'));

    if (env('SMTP_DEBUG') != 0) {
        $phpmailer->SMTPDebug = env('SMTP_DEBUG');
        $phpmailer->Debugoutput = function ($str, $level) {
            echo "PHPMailer debug level $level: $str\n";
        };
    }
});
