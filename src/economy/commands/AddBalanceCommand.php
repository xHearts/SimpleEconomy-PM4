<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class AddBalanceCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("addbalance.command");
        $this->setDescription("Add money to a player's balance.");
        $this->setPermission("addbalance.command");
        $this->setUsage("/addbalance");
        $this->setAliases(["addbal"]);
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("addbalance.command")){
            if(isset($args[0]) and isset($args[1])){
                if(is_string($args[0])){
                    if(is_numeric($args[1])){
                        $cm = Main::getDatabase();
                        if(($player = Server::getInstance()->getPlayerByPrefix($args[0])) instanceof Player){
                            $cm->addMoney($player->getName(), $args[1]);
                            $player->sendMessage("§aYou've been given: $" . number_format($args[1]) . ".");
                            $sender->sendMessage("§aGave: $" . number_format($args[1]) . " to " . $player->getName() . ".");
                        } else {
                            $cm->addMoney($args[0], $args[1]);
                            $sender->sendMessage("§aGave: $" . number_format($args[1]) . " to " . $args[0] . ".");
                        }
                    } else {
                        $sender->sendMessage("§cThe amount has to be a number.");
                        return false;
                    }
                } else {
                    $sender->sendMessage("§cThat is not a player's name.");
                    return false;
                }
            } else {
                $sender->sendMessage("§cUsage: /addbalance <player> <amount>");
                return false;
            }
        } else {
            $sender->sendMessage("§cYou don't have permission to use this command.");
            return false;
        }
        return true;
    }
}