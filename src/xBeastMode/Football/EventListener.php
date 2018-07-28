<?php

namespace xBeastMode\Football;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class EventListener implements Listener{

        public function damage(EntityDamageEvent $event){
                $target = $event->getEntity();
                if($target instanceof BallEntity){
                        $event->setCancelled();
                }
        }

}