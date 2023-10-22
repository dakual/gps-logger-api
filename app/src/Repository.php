<?php
namespace App;

use \PDO;

class Repository
{
  private $host;
  private $user;
  private $pass;
  private $dbname;

  public function __construct()
  {
    $this->host   = $_ENV['MYSQL_HOST'];
    $this->dbname = $_ENV['MYSQL_DATABASE'];
    $this->user   = $_ENV['MYSQL_USER'];
    $this->pass   = $_ENV['MYSQL_PASSWORD'];
    
    try {
      $connection_str = "mysql:host={$this->host};dbname={$this->dbname}";
      $this->database = new PDO($connection_str, $this->user, $this->pass);
      $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(\Exception $ex) {
      throw new \Exception(
        'Database failed: ' . $ex->getMessage(), 400
      );
    }
  }

  public function addLocation(Array $data): String
  {
    $query     = 'INSERT INTO `locations` (`device`, `latitude`, `longitude`, `accuracy`, `altitude`, `speed`, `provider`, `timestamp`) VALUES (:device, :latitude, :longitude, :accuracy, :altitude, :speed, :provider, :timestamp)';
    $statement = $this->database->prepare($query);
    $timestamp = date("Y-m-d H:i:s", intval($data['time']) / 1000);

    $statement->bindParam('device', $data['device']);
    $statement->bindParam('latitude', $data['latitude']);
    $statement->bindParam('longitude', $data['longitude']);
    $statement->bindParam('accuracy', $data['accuracy']);
    $statement->bindParam('altitude', $data['altitude']);
    $statement->bindParam('speed', $data['speed']);
    $statement->bindParam('provider', $data['provider']);
    $statement->bindParam('timestamp', $timestamp);
    $statement->execute();

    return "GPS data logged!";
  }

  public function getMap(String $device, int $accuracy, int $time, String $provider): array
  {
    $query = "
      SELECT 
        latitude AS lat, longitude AS lng 
      FROM `locations` 
      WHERE 
        device = '${device}' AND 
        accuracy <= ${accuracy} AND 
        provider = '${provider}' AND 
        timestamp > (SELECT MAX(timestamp) FROM locations WHERE device = '${device}') - INTERVAL ${time} HOUR 
      ORDER BY timestamp ASC
    ";
    
    $statement = $this->database->prepare($query);
    $statement->execute();

    $result = (array) $statement->fetchAll(\PDO::FETCH_CLASS) ?: [];

    return $result;
  }

  public function getDevices() {
    $query = "SELECT DISTINCT device FROM locations";
    
    $statement = $this->database->prepare($query);
    $statement->execute();

    $result = (array) $statement->fetchAll(\PDO::FETCH_CLASS) ?: [];

    return $result;
  } 
}