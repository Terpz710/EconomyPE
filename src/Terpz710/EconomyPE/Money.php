<?php

declare(strict_types=1);

namespace Terpz710\EconomyPE;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

use Terpz710\EconomyPE\Events\PlayersEvents;
use Terpz710\EconomyPE\Commands\RemoveMoney;
use Terpz710\EconomyPE\Commands\AddMoney;
use Terpz710\EconomyPE\Commands\Seemoney;
use Terpz710\EconomyPE\Commands\SetMoney;
use Terpz710\EconomyPE\Commands\TopMoney;
use Terpz710\EconomyPE\Commands\MyMoney;
use Terpz710\EconomyPE\Commands\Pay;

class Money extends PluginBase
{
    public static Config $money;
    public static Money $main;
    public static string $type;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents(new PlayersEvents(), $this);

        $this->getServer()->getCommandMap()->registerAll("EconomyPECommand",
            [
                new RemoveMoney($this),
                new AddMoney($this),
                new TopMoney($this),
                new Seemoney($this),
                new SetMoney($this),
                new MyMoney($this),
                new Pay($this)
            ]
        );

        self::$type = $this->getConfig()->get("money_save");

        if (self::$type === "mysql") {
            self::getDatabase()->query("CREATE TABLE IF NOT EXISTS user_money(name VARCHAR(255), money FLOAT)");
            self::getDatabase()->close();
        } else {
            self::$money = new Config($this->getDataFolder() . "Money.json", Config::JSON);
        }
    }

    public function onDisable(): void
    {
        if (self::$type === "mysql") {
            self::getDatabase()->close();
        }
    }

    public static function getDatabase(): ?\MySQLi
    {
        if (self::$type === "mysql") {
            return new \MySQLi(self::getInstance()->getConfig()->get("mysql-host"), self::getInstance()->getConfig()->get("mysql-user"), self::getInstance()->getConfig()->get("mysql-password"), self::getInstance()->getConfig()->get("mysql-database"));
        } else {
            return null;
        }
    }

    public static function getInstance(): Money
    {
        return self::$main;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$main->getConfig()->get("prefix"), self::$main->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function hasPermissionPlayer(Player $player, string $perm): bool
    {
        if (self::getInstance()->getServer()->isOp($player->getName())) {
            return false;
        }
        if ($player->hasPermission($perm)) {
            return false;
        } else {
            $player->sendMessage(self::getConfigReplace("no_perm"));
            return true;
        }
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) {
            return $player->getName();
        } else {
            return $player;
        }
    }

    public static function getConfigValue(string $path): array|int|string|bool
    {
        return self::$main->getConfig()->get($path);
    }

    public static function createPlayer($player): void
    {
        if (self::$type === "mysql") {
            $name = self::getPlayerName($player);
            $money = self::getConfigValue("money_default");
            self::getDatabase()->query("INSERT INTO user_money(name, money) VALUES ('$name', '$money')");
            self::getDatabase()->close();
        } else {
            if (!self::existPlayer($player)) {
                self::setMoney($player, self::getConfigValue("money_default"));
            }
        }
    }

    public static function existPlayer($player): bool
    {
        if (self::$type === "mysql") {
            $name = self::getPlayerName($player);
            $result = self::getDatabase()->query("SELECT * FROM user_money WHERE name='$name'");
            self::getDatabase()->close();
            return $result->num_rows > 0;
        } else {
            return self::$money->exists(self::getPlayerName($player));
        }
    }

    public static function getMoneyPlayer($player): int
    {
        if (self::$type === "mysql") {
            $name = self::getPlayerName($player);
            $money = self::getDatabase()->query("SELECT money FROM user_money WHERE name='$name'");
            $ret = $money->fetch_array()[0] ?? false;
            $money->free();
            self::getDatabase()->close();
            return ($ret !== false) ? (int)$ret : 0;
        } else {
            return self::$money->get(self::getPlayerName($player), 0);
        }
    }

    public static function addMoney($player, int $amount): void
    {
        self::setMoney($player, self::getMoneyPlayer($player) + $amount);
        if ($player instanceof Player) {
            $player = $player->getName();
        }
    }

    public static function removeMoney($player, int $amount): void
    {
        self::setMoney($player, self::getMoneyPlayer($player) - $amount);
        if ($player instanceof Player) {
            $player = $player->getName();
        }
    }

    public static function setMoney($player, int $amount): void
    {
        if (self::$type === "mysql") {
            $name = self::getPlayerName($player);
            self::getDatabase()->query("UPDATE user_money SET money='$amount' WHERE name='$name'");
            self::getDatabase()->close();
        } else {
            self::$money->set(self::getPlayerName($player), $amount);
            self::$money->save();
            if ($player instanceof Player) {
                $player = $player->getName();
            }
        }
    }
}
