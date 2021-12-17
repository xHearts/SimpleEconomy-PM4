<?php

namespace economy\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use economy\Main;

class PayCommand extends Command{

    public function __construct($name){
        parent::__construct($name);
        Main::registerPermission("pay.command");
        $this->setDescription("Pay someone an amount of money.");
        $this->setPermission("pay.command");
        $this->setUsage("/pay");
        // Made the pay command bc Rinier is lazy..
    }
    
    public function execute(CommandSender $sender, string $label, array $args) : bool{
        if(Server::getInstance()->isOp($sender->getName()) or $sender->hasPermission("pay.command")){
            if($sender instanceof Player){
                if(isset($args[0])){
                    if(($player = Server::getInstance()->getPlayerByPrefix($args[0])) instanceof Player){
                        $senderMoney = Main::getDatabase()->getMoney($sender->getName());
                        if(isset($args[1])){
                            if(is_numeric($args[1])){
                                if($senderMoney >= $args[1]){
                                    Main::getDatabase()->removeMoney($sender->getName(), $args[1]);
                                    Main::getDatabase()->addMoney($sender->getName(), $args[1]);
                                    // Send sent money message to both users
                                } else {
                                    // The sender doesn't have enough money
                                    return false;
                                }
                            } else {
                                // Argument 2 isn't a number
                                return false;
                            }
                        } else {
                            // Hasn't set the money amount
                            return false;
                        }
                    } else {
                        // Player chosen isn't online or doesn't exist
                        return false;
                    }
                } else {
                    // Hasn't specified a player
                    return false;
                }
            } else {
                // Not a Player
                return false;
            }
        } else {
            // No perm
            return false;
        }
    }