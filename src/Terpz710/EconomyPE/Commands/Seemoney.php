<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use pocketmine\Server;

use Terpz710\EconomyPE\Money;

class Seemoney extends Command implements PluginOwned
{

    private $plugin;
    
    public function __construct(Money $plugin)
    {
        $this->plugin = $plugin;
        $command = explode(":", Money::getConfigReplace("seemoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("seemoney_aliases"));
        $this->setPermission("economype.cmd.seemoney");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("seemoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
                if (Money::existPlayer($name)) {
                    $sender->sendMessage(Money::getConfigReplace("seemoney_msg", [strtolower("{money}"), strtolower("{player}")], [Money::getMoneyPlayer($name), $name]));
                } else $sender->sendMessage(Money::getConfigReplace("no_exist_player"));
            } else $sender->sendMessage(Money::getConfigReplace("no_args_player"));
        }
    }
}
