<p align="center">
    <a href="https://github.com/Terpz710/EconomyPE"><img src="https://github.com/Terpz710/EconomyPE/blob/main/icon.png"></img></a><br>
    <b>EconomyManagement plugin for Pocketmine-MP</b>
  
# Description
[EconomyPE](https://github.com/Terpz710/EconomyPE) is a [Pocketmine-MP](https://pmmp.io) plugin that adds in basic economy features to your server! Such as a way to send(pay) money, see player balances and see the top balances on the server. For the OP(operator) you can remove, set and add money to users!
Everything is configurable through the config yml. Both Json and MySQL are supported! Inspiration by [EconomyAPI](https://poggit.pmmp.io/p/EconomyAPI/5.7.1-3)! Thank you [OneBone](https://github.com/onebone)!

# ScoreHud Tags

[ScoreHud](https://poggit.pmmp.io/p/ScoreHud) is supported!

[CustomScore](https://poggit.pmmp.io/p/CustomScore/1.0.1): Tell [Joshet18](https://github.com/Joshet18) to integrate [EconomyPE](https://github.com/Terpz710/EconomyPE) into [CustomScore](https://poggit.pmmp.io/p/CustomScore/1.0.1)

Tags:

{EconomyPE.balance}

# Permissions/Commands
```
/pay

economype.cmd.pay

defualt: true

/mymoney

economype.cmd.mymoney

default: true

/seemoney

economype.cmd.seemoney

default: true

/topmoney

economype.cmd.topmoney

default: true

/addmoney

economype.cmd.addmoney

default: op

/removemoney

economype.cmd.removemoney

default: op

/setmoney

economype.cmd.setmoney

default: op
```

# API for Developers! ❤️

```
Get the players balance:
Money::getInstance()->getMoneyPlayer($player);

Add money to a player balance:
Money::getInstance()->addMoney($player, $amount);

Remove money from a player balance:
Money::getInstance()->removeMoney($player, $amount);

Set the player balance:
Money::getInstance()->setMoney($player, $amount);

Check if the player exist:
Money::getInstance()->existPlayer($player);

Create a new player balance:
Money::getInstance()->createPlayer($player);
```
