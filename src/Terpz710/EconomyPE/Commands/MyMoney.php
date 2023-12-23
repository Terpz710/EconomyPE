<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;

use Terpz710\EconomyPE\Money;

class MyMoney extends Command implements PluginOwned
{

    private $plugin;
    
    public function __construct(Money $plugin)
    {
        $this->plugin = $plugin;
        $command = explode(":", Money::getConfigReplace("mymoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("mymoney_aliases"));
        $this->setPermission("economype.cmd.mymoney");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("mymoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;
            $sender->sendMessage(Money::getConfigReplace("mymoney_msg", [strtolower("{money}")], [Money::getMoneyPlayer($sender)]));
        }
    }
}
