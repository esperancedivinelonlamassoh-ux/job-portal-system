<?php
include("DB.php");
session_start();
if(!isset($_SESSION['user_type']) || $_SESSION['user_type']!=='org_user'){ header('Location: login_org.php'); exit; }
$id = intval($_GET['id'] ?? 0);
$act = $_GET['action'] ?? '';
$pdo = DB::get();
if($act==='approve'){
  $stmt = $pdo->prepare('UPDATE applications SET status = ? WHERE id = ?');
  $stmt->execute(['approved',$id]);
} elseif($act==='reject') {
  $stmt = $pdo->prepare('UPDATE applications SET status = ? WHERE id = ?');
  $stmt->execute(['rejected',$id]);
}
header('Location: org_view_applications.php?job_id='.intval($_GET['job_id'] ?? 0));
exit;
?>