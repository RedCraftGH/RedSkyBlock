<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Utils\IslandManager;

class Island {

  public const MEMBER_RANKS = ["member", "helper", "moderator", "admin"];
  public const MEMBER_PERMISSIONS = ["island.place", "island.break", "island.interact", "island.kick", "island.ban", "island.spawn", "island.lock", "island.name"];

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
  private $permissions;

  private $defaultSettings = array(
    "pvp" => false,
    "safevoid" => true,
    "visitor_pickup" => false
  );
  private $defaultStats = array(
    "blocks_broken" => 0,
    "blocks_placed" => 0
  );
  private $defaultPermissions = array(
    "member" => ["island.place", "island.break", "island.interact"],
    "helper" => ["island.place", "island.break", "island.interact", "island.kick"],
    "moderator" => ["island.place", "island.break", "island.interact", "island.kick", "island.ban", "island.lock"],
    "admin" => ["island.place", "island.break", "island.interact", "island.kick", "island.ban", "island.lock", "island.spawn", "island.name"]
  );

  private $invited = [];
  private $islandChat = [];

  public function __construct(array $islandData) {

    $this->creator = (string) $islandData["creator"];
    $this->name = (string) $islandData["name"];
    if ($this->name === "") {

      $this->name = $this->creator . "'s Island";
    }
    $this->size = (int) $islandData["size"];
    $this->value = (int) $islandData["value"];
    $this->initialSpawnPoint = (array) $islandData["initialspawnpoint"];
    if ($this->initialSpawnPoint === []) {

      IslandManager::getInstance()->deleteIsland($this);
    }
    $this->spawnPoint = (array) $islandData["spawnpoint"];
    if ($this->spawnPoint === []) {

      $this->spawnPoint = $this->initialSpawnPoint;
    }
    $this->members = (array) $islandData["members"];
    $this->banned = (array) $islandData["banned"];
    $this->resetCooldown = (int) $islandData["resetcooldown"];
    $this->lockStatus = (bool) $islandData["lockstatus"];
    $this->settings = (array) $islandData["settings"];
    $this->stats = (array) $islandData["stats"];
    $this->permissions = (array) $islandData["permissions"];

    if (count($this->settings) < count($this->defaultSettings)) {

      $defaultSettings = array_keys($this->defaultSettings);
      $savedSettings = array_keys($this->settings);
      $compare = array_diff($defaultSettings, $savedSettings);
      foreach ($compare as $setting) {

        $this->settings[$setting] = $this->defaultSettings[$setting];
      }
    }
    if (count($this->stats) < count($this->defaultStats)) {

      $defaultStats = array_keys($this->defaultStats);
      $savedStats = array_keys($this->stats);
      $compare = array_diff($defaultStats, $savedStats);
      foreach ($compare as $stat) {

        $this->stats[$stat] = $this->defaultStats[$stat];
      }
    }
    if (count($this->permissions) < count($this->defaultPermissions)) {

      $defaultPermissions = array_keys($this->defaultPermissions);
      $savedPermissions = array_keys($this->permissions);
      $compare = array_diff($defaultPermissions, $savedPermissions);
      foreach ($compare as $permission) {

        $this->permissions[$permission] = $this->defaultPermissions[$permission];
      }
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

    return $this->members;
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

  public function setRank(string $name, string $rank): void {

    $this->members[$name] = $rank;
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

  public function getDefaultSettings(): array {

    return $this->defaultSettings;
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

  public function getChatters(): array {

    return $this->islandChat;
  }

  public function addChatter(string $playerName): void {

    $playerName = strtolower($playerName);
    if (!in_array($playerName, $this->islandChat)) {

      $this->islandChat[] = $playerName;
    }
  }

  public function removeChatter(string $playerName): void {

    $playerName = strtolower($playerName);
    if (in_array($playerName, $this->islandChat)) {

      $index = array_search($playerName, $this->islandChat);
      unset($this->islandChat[$index]);
    }
  }

  public function getPermissions(): array {

    return $this->permissions;
  }

  public function removePermission(string $rank, string $permission): bool {

    if (in_array($permission, self::MEMBER_PERMISSIONS)) {

      if (array_key_exists($rank, $this->permissions)) {

        if (in_array($permission, $this->permissions[$rank])) {

          $index = array_search($permission, $this->permissions[$rank]);
          unset($this->permissions[$rank][$index]);
          return true;
        } else {

          return false;
        }
      } else {

        return false;
      }
    } else {

      return false;
    }
  }

  public function addPermission(string $rank, string $permission): bool {

    if (in_array($permission, self::MEMBER_PERMISSIONS)) {

      if (array_key_exists($rank, $this->permissions)) {

        if (!in_array($permission, $this->permissions[$rank])) {

          $this->permissions[$rank][] = $permission;
          return true;
        } else {

          return false;
        }
      } else {

        return false;
      }
    } else {

      return false;
    }
  }
}
