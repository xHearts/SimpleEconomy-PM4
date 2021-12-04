<?php

namespace economy;

use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;

use economy\data\SQLProvider;
use economy\EventListener;
use economy\commands\BalanceCommand;
use economy\commands\AddBalanceCommand;
use economy\commands\RemoveBalanceCommand;
use economy\commands\SetBalanceCommand;
use economy\commands\TopBalanceCommand;

class Main extends PluginBase{

    public static $instance;
    public static $sqlProvider;

    public function onEnable() : void{
        self::$instance = $this;
        $this->checkFiles();
        $this->loadSQLProvider();
        $this->loadCommands();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getLogger()->info("SimpleEconomy has been enabled");
    }

    public function checkFiles() : void{
        @mkdir($this->getDataFolder() . "players/");
    }

    public function loadSQLProvider() : void{
        self::$sqlProvider = new SQLProvider();
    }

    public static function getDatabase(){
        return self::$sqlProvider;
    }

    public function loadCommands() : void{
        $command = $this->getServer()->getCommandMap();

        $command->register("balance", new BalanceCommand("balance"));
        $command->register("addbalance", new AddBalanceCommand("addbalance"));
        $command->register("removebalance", new RemoveBalanceCommand("removebalance"));
        $command->register("setbalance", new SetBalanceCommand("setbalance"));
        $command->register("topbalance", new TopBalanceCommand("topbalance"));
    }

    public static function registerPermission(string $permission) : void{
        $instance = new Permission($permission);
        $manager = PermissionManager::getInstance();
        $manager->addPermission($instance);
    }
}