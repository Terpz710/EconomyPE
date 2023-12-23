<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE\Events;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;

use Terpz710\EconomyPE\Money;

class PlayersEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        Money::createPlayer($player);
        Money::getInstance()->updateScoreHudTags($player);
    }
}
