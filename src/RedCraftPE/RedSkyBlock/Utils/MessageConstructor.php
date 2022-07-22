<?php

namespace RedCraftPE\RedSkyBlock\Utils;

use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;

class MessageConstructor {

  private $messages;
  public static $instance;
  public $plugin;

  private $messageIds = [];

  public function __construct(SkyBlock $plugin) {

    $this->plugin = $plugin;
    $this->messages = $plugin->messages;
    self::$instance = $this;
    $this->updateMessages();
  }

  public static function getInstance(): MessageConstructor {

    return self::$instance;
  }

  public function construct(string $identifier): string {

    $plugin = $this->plugin;
    $message = $plugin->messages->get($identifier);
    if ($message == null) return "Message Not Set";

    $message = str_replace("{NEW_LINE}", TextFormat::EOL, $message);
    $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
    $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
    $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
    $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
    $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
    $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
    $message = str_replace("{ORANGE}", TextFormat::GOLD, $message);
    $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
    $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
    $message = str_replace("{BLUE}", TextFormat::BLUE, $message);
    $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
    $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
    $message = str_replace("{RED}", TextFormat::RED, $message);
    $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
    $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
    $message = str_replace("{WHITE}", TextFormat::WHITE, $message);
    $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
    $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
    $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
    $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
    $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
    $message = str_replace("{RESET}", TextFormat::RESET, $message);
    return $message;
  }

  public function updateMessages(): void {

    $realString = file_get_contents($this->plugin->getDataFolder() . "../RedSkyBlock/messages.yml");
    $realArray = yaml_parse($realString);
    $realString = substr($realString, 0, -3);
    $realKeys = array_keys($realArray);

    $reference = yaml_parse(stream_get_contents($this->plugin->getResource("messages.yml")));
    $referenceKeys = array_keys($reference);

    $compare = array_diff($referenceKeys, $realKeys);

    if (count($compare) > 0) {

      foreach ($compare as $key) {

        $realString .= $key . ": " . "\"" . $reference[$key] . "\"\n";
      }
      $realString .= "\n---";

      file_put_contents($this->plugin->getDataFolder() . "../RedSkyBlock/messages.yml", $realString);
      $this->plugin->messages->reload();
    }
  }
}
