<<<<<<< HEAD
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "job_portal"; // use your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
// src/lib/DB.php
class DB {
  private static $pdo = null;
  public static function get() {
    if (self::$pdo === null) {
      $host = getenv('DB_HOST') ?: '127.0.0.1';
      $db   = getenv('DB_NAME') ?: 'job_portal';
      $user = getenv('DB_USER') ?: 'root';
      $pass = getenv('DB_PASS') ?: '';
      $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
      $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
      self::$pdo = new PDO($dsn, $user, $pass, $opts);
    }
    return self::$pdo;
  }
}
=======
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "job_portal"; // use your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
// src/lib/DB.php
class DB {
  private static $pdo = null;
  public static function get() {
    if (self::$pdo === null) {
      $host = getenv('DB_HOST') ?: '127.0.0.1';
      $db   = getenv('DB_NAME') ?: 'job_portal';
      $user = getenv('DB_USER') ?: 'root';
      $pass = getenv('DB_PASS') ?: '';
      $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
      $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
      self::$pdo = new PDO($dsn, $user, $pass, $opts);
    }
    return self::$pdo;
  }
}
>>>>>>> 76038d0 (commit changes)
?>