<?php

declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Gender extends Enum
{
    const MALE = 0;
    const FEMALE = 1;
    const OTHER = 2;
}
