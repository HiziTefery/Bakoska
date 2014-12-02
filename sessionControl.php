
<?PHP
session_start();
if (!(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '')) {
header ("Location: login2.php");
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    header ("Location: login2.php");
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
