<?php

declare(strict_types=1);

namespace App\Enums\Notification;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Type extends Enum
{
    const PURCHASES = 0;
    const SALES = 1;
    const FOLLOWERS = 2;
    const FOLLOWING = 3;
    const RESERVED = 4;
    const LIVE_AUCTION = 5;
}
