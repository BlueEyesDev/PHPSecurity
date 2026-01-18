<?php
class PDO_htmlspecialchars extends PDOStatement {
    protected function __construct() {}

    public function fetch(
        int $mode = PDO::FETCH_DEFAULT,
        int $cursorOrientation = PDO::FETCH_ORI_NEXT,
        int $cursorOffset = 0
    ): mixed {
        $fetch = parent::fetch($mode, $cursorOrientation, $cursorOffset);

        if ($fetch === false) {
            return false;
        }

        foreach ($fetch as $key => $value) {
            if (is_string($value)) {
                $fetch[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        return $fetch;
    }

    public function fetchAll(
        int $mode = PDO::FETCH_DEFAULT,
        mixed ...$args
    ): array {
        $fetchAll = parent::fetchAll($mode, ...$args);

        foreach ($fetchAll as $rowKey => $row) {
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    $fetchAll[$rowKey][$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }
        }
        return $fetchAll;
    }
}
$PDO = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_STATEMENT_CLASS => [PDO_htmlspecialchars::class]
]);
