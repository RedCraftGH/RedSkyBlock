<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\player\Player;

class Island {

  private $creator;
  private $name;
  private $size;
  private $value;
  private $initialSpawnPoint;
  private $spawnPoint;
  private $members;
  private $banned;
  private $resetCooldown;
  private $lockStatus;
  private $settings;
  private $stats;

  private $defaultSettings = array(
    "pvp" => false,
    "safevoid" => true,
    "visitor_pickup" => false
  );
  private $defaultStats = array(
    "blocks_broken" => 0,
    "blocks_placed" => 0
  );

  private $invited = [];

  public function __construct(array $islandData) {

    $this->creator = $islandData["creator"];
    $this->name = $islandData["name"];
    $this->size = $islandData["size"];
    $this->value = $islandData["value"];
    $this->initialSpawnPoint = $islandData["initialspawnpoint"];
    $this->spawnPoint = $islandData["spawnpoint"];
    $this->members = $islandData["members"];
    $this->banned = $islandData["banned"];
    $this->resetCooldown = $islandData["resetcooldown"];
    $this->lockStatus = $islandData["lockstatus"];
    $this->settings = (array) $islandData["settings"];
    $this->stats = (array) $islandData["stats"];

    if ($this->settings === []) {

      $this->settings = $this->defaultSettings;
    }
    if ($this->stats === []) {

      $this->stats = $this->defaultStats;
    }
  }

  public function getCreator(): string {

    return $this->creator;
  }

  public function getName(): string {

    return $this->name;
  }

  public function setName(string $name): void {

    $this->name = $name;
  }

  public function getSize(): int {

    return $this->size;
  }

  public function setSize(int $size): void {

    $this->size = $size;
  }

  public function getValue(): int {

    return $this->value;
  }

  public function addValue(int $value): void {

    $this->value += $value;
  }

  public function removeValue(int $value): void {

    if ($this->value - $value < 0) {

      $this->value = 0;
    } else {

      $this->value -= $value;
    }
  }

  public function getInitialSpawnPoint(): array {

    return $this->initialSpawnPoint;
  }

  public function getIslandCenter(): array { //for readability

    $center = [$this->initialSpawnPoint[0], $this->initialSpawnPoint[2]];
    return $center;
  }

  public function getSpawnPoint(): array {

    return $this->spawnPoint;
  }

  public function setSpawnPoint(array $spawnPoint): void {

    $this->spawnPoint = $spawnPoint;
  }

  public function getMembers(): array {

    return (array) $this->members;
  }

  public function addMember(string $name): bool {

    if (!array_key_exists(strtolower($name), $this->members) && strtolower($name) !== strtolower($this->creator)) {

      $this->members[strtolower($name)] = "member"; //done this way for future promotion system
      return true;
    } else {

      return false;
    }
  }

  public function removeMember(string $name): bool {

    if (array_key_exists(strtolower($name), $this->members)) {

      unset($this->members[strtolower($name)]);
      return true;
    } else {

      return false;
    }
  }

  public function getBanned(): array {

    return $this->banned;
  }

  public function ban(string $name): bool {

    if (!in_array(strtolower($name), $this->banned)) {

      array_push($this->banned, strtolower($name));
      return true;
    } else {

      return false;
    }
  }

  public function unban(string $name): bool {

    if (in_array(strtolower($name), $this->banned)) {

      $index = array_search(strtolower($name), $this->banned);
      unset($this->banned[$index]);
      return true;
    } else {

      return false;
    }
  }

  public function getResetCooldown(): int {

    return $this->resetCooldown;
  }

  public function getLockStatus(): bool {

    return $this->lockStatus;
  }

  public function lock(): bool {

    if ($this->lockStatus === false) {

      $this->lockStatus = true;
      return true;
    } else {

      return false;
    }
  }

  public function unlock(): bool {

    if ($this->lockStatus === true) {

      $this->lockStatus = false;
      return true;
    } else {

      return false;
    }
  }

  public function invite(string $name): bool {

    $name = strtolower($name);
    if (!in_array($name, $this->invited)) {

      $this->invited[] = $name;
      return true;
    } else {

      return false;
    }
  }

  public function acceptInvite(Player $player): bool {

    $playerName = strtolower($player->getName());
    $memberCount = count($this->members);
    if (in_array($playerName, $this->invited)) {

      $this->addMember($playerName);
      $index = array_search($playerName, $this->invited);
      unset($this->invited[$index]);
      return true;
    } else {

      return false;
    }
  }

  public function getSettings(): array {

    return $this->settings;
  }

  public function changeSetting(string $setting, bool $bias): void {

    $this->settings[$setting] = $bias;
  }

  public function resetSettings(): void {

    $this->settings = $this->defaultSettings;
  }

  public function getStats(): array {

    return $this->stats;
  }

  public function addToStat(string $stat, int $amount): void {

    $this->stats[$stat] += $amount;
  }

  public function removeFromStat(string $stat, int $amount): void {

    $this->stats[$stat] -= $amount;
  }
}
