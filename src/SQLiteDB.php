<?php
namespace Gugusd999\Web;

class SQLiteDB {
    private static $pdo;

    function __construct($dbname="") {
      (new self)->connect($dbname);
    }

    public static function connect($dbName="") {
      try {
          // Jika file database tidak ada, maka akan dibuat
          if (!file_exists($dbName)) {
              $pdo = new PDO("sqlite:$dbName");
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              self::createDatabaseSchema($pdo); // Buat skema database jika perlu
          } else {
              $pdo = new PDO("sqlite:$dbName");
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          }
          self::$pdo = $pdo;
      } catch(PDOException $e) {
          throw new Exception("Connection failed: " . $e->getMessage());
      }
  }

    public static function createTable($tableName="", $fields="") {
        $fieldsString = implode(",", $fields);
        $query = "CREATE TABLE IF NOT EXISTS $tableName ($fieldsString)";
        self::$pdo->exec($query);
    }

    public static function query($sql="", $params = []) {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function insert($tableName="", $data=[]) {
        $fields = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO $tableName ($fields) VALUES ($placeholders)";
        self::query($sql, array_values($data));
    }

    public static function update($tableName="", $data=[], $condition="") {
        $setString = "";
        foreach ($data as $key => $value) {
            $setString .= "$key=?,";
        }
        $setString = rtrim($setString, ",");
        $sql = "UPDATE $tableName SET $setString WHERE $condition";
        self::query($sql, array_values($data));
    }

    public static function createTrigger($triggerName, $sql) {
        $query = "CREATE TRIGGER IF NOT EXISTS $triggerName $sql";
        self::$pdo->exec($query);
    }

    public static function createFunction($functionName, $callback, $numArgs = -1) {
        self::$pdo->sqliteCreateFunction($functionName, $callback, $numArgs);
    }

    public static function get($stmt) {
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
