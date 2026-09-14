<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * A file upload was rejected. The message is safe to show to the end user.
 */
final class UploadException extends RuntimeException
{
}
