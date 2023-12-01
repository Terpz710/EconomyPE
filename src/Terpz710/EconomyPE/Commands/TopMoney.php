<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Terpz710\EconomyPE\Money;

class TopMoney extends Command
{
    public function __construct()
    {
        $command = explode(":", Money::getConfigReplace("topmoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("topmoney_aliases"));
        $this->setPermission("economype.topmoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("topmoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;

            $sender->sendMessage(Money::getConfigReplace("topmoney_title"));

            if (Money::$type === "mysql") {
                $res = Money::getDatabase()->query("SELECT * FROM money");

                $ret = [];
                foreach($res->fetch_all() as $val){
                    $ret[$val[0]] = $val[1];
                }

                $res->free();

                $money = $ret;
            } else $money = Money::$money->getAll();

            $i = 1;
            arsort($money);
            foreach ($money as $name => $value) {
                if ($i !== Money::getConfigValue("tommoney_num") + 1) {
                    $sender->sendMessage(Money::getConfigReplace("topmoney_msg", [strtolower("{num}"), strtolower("{player}"), strtolower("{money}")], [$i, $name, $value]));
                    $i++;
                } else break;
            }
        }
    }
}