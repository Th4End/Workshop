<?php
class Room {
    private static $table = 'rooms';

    public static function getAll() {
        $db = Database::getConnection();
        $query = "SELECT * FROM " . self::$table;
        $result = $db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $query = "SELECT * FROM " . self::$table . " WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
