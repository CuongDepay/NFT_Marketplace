<?php

declare(strict_types=1);

namespace App\Enums\NFT;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Status extends Enum
{
    const HAS_OFFERS = 0;
    const NEW_IN = 1;
    const TRENDING = 2;
    const LIVE_AUCTION = 3;
}
