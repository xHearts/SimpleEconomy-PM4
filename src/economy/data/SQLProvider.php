<?php

namespace economy\data;

use economy\Main;

use SQLite3;

class SQLProvider{

    public $db;

    public function __construct(){
        $this->db = new SQLite3(Main::$instance->getDataFolder() . "players/" . "Database.db");
        $this->createTables();
    }

    public function createTables() : void{
        $this->db->exec("CREATE TABLE IF NOT EXISTS money(name TEXT PRIMARY KEY, money INT)");
    }

    public function getMoney(string $pname){
        $name = strtolower($pname);
        $result = $this->db->query("SELECT * FROM money WHERE name = '$name';");
        $array = $result->fetchArray(SQLITE3_ASSOC);
        return intval($array["money"] ?? 0);
    }

    public function addMoney(string $pname, $amount) : void{
        $name = strtolower($pname);
        $money = $this->getMoney($name);
        $this->db->exec("INSERT OR REPLACE INTO money(name, money) VALUES('$name', " . $money + $amount . ");");
    }

    public function setMoney(string $pname, $amount) : void{
        $name = strtolower($pname);
        $this->db->exec("INSERT OR REPLACE INTO money(name, money) VALUES('$name', " . $amount . ");");
    }

    public function removeMoney(string $pname, $amount) : void{
        $name = strtolower($pname);
        $money = $this->getMoney($name);
        $this->db->exec("INSERT OR REPLACE INTO money(name, money) VALUES('$name', " . $money - $amount . ");");
    }

    public function getTopMoney($player) : void{
        $result = $this->db->query("SELECT name FROM money ORDER BY money DESC LIMIT 10;");
        $i = 0;
        $player->sendMessage("§l§8[§aTop Money§8]\n§r§7(Richest people on the server)");
        while ($resultArr = $result->fetchArray(SQLITE3_ASSOC)) {
            $name = $resultArr['name'];
            $money = $this->getMoney($name);
            $i++;
            $player->sendMessage("§l§8[§r§a#" . $i . "§l§8] §r§a" . $name . " §l§8| §r§e$" . number_format($money). "\n");
        }
    }

}