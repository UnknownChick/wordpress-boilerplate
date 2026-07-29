<?php

declare(strict_types=1);

namespace Theme\Core;

defined('ABSPATH') || die();

use Exception;

/**
 * Thrown from inside an AjaxController::handle() implementation to
 * short-circuit the request with a JSON error response.
 */
class AjaxException extends Exception
{
}
