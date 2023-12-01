<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

use Terpz710\EconomyPE\Money;

class SetMoney extends Command
{
    public function __construct()
    {
        $command = explode(":", Money::getConfigReplace("setmoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("setmoney_aliases"));
        $this->setPermission("economype.setmoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("setmoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
                if (Money::existPlayer($name)) {
                    if (isset($args[1])) {
                        if (is_numeric($args[1])) {
                            Money::setMoney($name, intval($args[1]));
                            $sender->sendMessage(Money::getConfigReplace("setmoney_msg", [strtolower("{money}"), strtolower("{player}")], [intval($args[1]), $name])); // Cast to int
                        } else $sender->sendMessage(Money::getConfigReplace("is_numeric"));
                    } else $sender->sendMessage(Money::getConfigReplace("no_args_player"));
                } else $sender->sendMessage(Money::getConfigReplace("no_exist_player"));
            } else $sender->sendMessage(Money::getConfigReplace("no_args_player"));
        }
    }
}
