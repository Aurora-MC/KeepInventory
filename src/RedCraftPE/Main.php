<?php

namespace RedCraftPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener {

  public function onEnable(): void {
  
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    //make config below this point:
    
    if (!file_exists($this->getDataFolder() . "config.yml")) {
      
      @mkdir($this->getDataFolder());
      $this->saveResource("config.yml");
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
      $this->cfg->set("KeepInventory", false);
    } else {
      
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    
    $this->cfg->save();
    $this->cfg->reload();
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
  
    switch (strtolower($command->getName())) {
    
      case "keepinventory":
        
        if (!$args) {
        
          return false;
        } else {
        
          switch ($args[0]) {
          
            case "toggle":
              
              if ($sender->hasPermission("keepinventory.*")) {
                
                if (!$args[1]) {

                  $sender->sendMessage(TextFormat::WHITE . "Usage: /ki toggle <on/off>");
                  return true;
                } elseif ($args[1] === "on") {

                  $this->cfg->set("KeepInventory", true);
                  $this->cfg->save();
                  $sender->sendMessage(TextFormat::GREEN . "KeepInventory has been enabled on your server!");
                  return true;
                } elseif ($args[1] === "off") {

                  $this->cfg->set("KeepInventory", false);
                  $this->cfg->save();
                  $sender->sendMessage(TextFormat::GREEN . "KeepInventory has been disabled on your server!");
                  return true;
                } else {

                  $sender->sendMessage(TextFormat::WHITE . "Usage: /ki toggle <on/off>");
                  return true;
                }
              }
              break;
          }
        }
        break;
    }
    return false; //Extra protection
  }
  public function onDeath(PlayerDeathEvent $event) {
  
    if ($this->cfg->get("KeepInventory") === true) {
    
      $event->setKeepInventory(true);
    } else {
    
      return;
    }
  }
}
