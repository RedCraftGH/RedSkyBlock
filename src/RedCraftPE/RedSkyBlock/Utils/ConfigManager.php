<?php

namespace RedCraftPE\RedSkyBlock\Utils;

use RedCraftPE\RedSkyBlock\SkyBlock;

class ConfigManager {

  private $plugin;

  public function __construct(SkyBlock $plugin) {

    $this->plugin = $plugin;
    $this->verifyConfig();
  }

  public function verifyConfig(): void {

    $real = yaml_parse(file_get_contents($this->plugin->getDataFolder() . "../RedSkyBlock/config.yml"));
    $realKeys = array_keys($real);

    $reference = yaml_parse(stream_get_contents($this->plugin->getResource("config.yml")));
    $referenceKeys = array_keys($reference);

    $compare = array_diff($referenceKeys, $realKeys);

    if (count($compare) > 0) {

      foreach ($compare as $key) {

        $real[$key] = $reference[$key];
      }
      $updated = yaml_emit($real);
      file_put_contents($this->plugin->getDataFolder() . "../RedSkyBlock/config.yml", $updated);
    }
  }
}
