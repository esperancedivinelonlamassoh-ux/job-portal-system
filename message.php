<?php
session_start();

/* --------------------
   DATABASE CONNECTION
-------------------- */
$pdo = new PDO("mysql:host=localhost;dbname=job_portal", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* --------------------
   REGISTER USER
-------------------- */
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);

    echo "<script>alert('Account created successfully!');</script>";
}

/* --------------------
   LOGIN USER
-------------------- */
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
    } else {
        echo "<script>alert('Invalid login');</script>";
    }
}

/* --------------------
   AJAX: SEND MESSAGE
-------------------- */
if (isset($_POST["send_message"])) {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION["user_id"], $_POST["receiver_id"], $_POST["message"]]);
    exit();
}

/* --------------------
   AJAX: GET MESSAGES
-------------------- */
if (isset($_POST["fetch_messages"])) {
    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE (sender_id=? AND receiver_id=?) 
        OR (sender_id=? AND receiver_id=?) 
        ORDER BY created_at ASC
    ");
    $stmt->execute([
        $_SESSION["user_id"], $_POST["receiver_id"],
        $_POST["receiver_id"], $_SESSION["user_id"]
    ]);

    $messages = $stmt->fetchAll();
    echo json_encode($messages);
    exit();
}

/* --------------------
   AJAX: GET USER LIST
-------------------- */
if (isset($_POST["get_users"])) {
    $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id != ?");
    $stmt->execute([$_SESSION["user_id"]]);
    echo json_encode($stmt->fetchAll());
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Cameroon Messaging System</title>
<style>
body { margin:0; font-family:Arial; background:#f4f4f4; }
.header { background:#007000; color:white; padding:15px; text-align:center; font-size:24px; }
.container { display:flex; height:calc(100vh - 60px); }
.sidebar { width:25%; background:white; overflow-y:auto; border-right:2px solid #ddd; }
.user-item { padding:15px; border-bottom:1px solid #eee; cursor:pointer; }
.user-item:hover { background:#e8ffe8; }
.chat-area { width:75%; display:flex; flex-direction:column; background:#fafafa; }
.messages { flex:1; padding:20px; overflow-y:auto; }
.msg { padding:10px; border-radius:10px; margin-bottom:10px; max-width:60%; }
.sent { background:#ffce00; align-self:flex-end; }
.received { background:white; align-self:flex-start; }
.input-area { display:flex; padding:10px; background:white; border-top:2px solid #ddd; }
input { flex:1; padding:10px; font-size:16px; }
button { padding:10px 20px; background:#d10000; color:white; border:none; }
</style>
</head>

<body>

<?php if (!isset($_SESSION["user_id"])): ?>

<!-- -----------------------------------------------
     LOGIN + REGISTER PAGE
------------------------------------------------ -->
<div class="header">🇨🇲 Cameroon Job Portal Messaging</div>

<div style="width:40%; margin:50px auto; background:white; padding:20px; border-radius:10px;">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button name="login">Login</button>
    </form>
    <hr><br>

    <h2>Create Account</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <select name="role" required>
            <option value="applicant">Applicant</option>
            <option value="employer">Employer</option>
        </select><br><br>
        <button name="register">Register</button>
    </form>
</div>

<?php else: ?>

<!-- -----------------------------------------------
     MESSAGING PANEL
------------------------------------------------ -->
<div class="header">🇨🇲 Welcome <?=$_SESSION["username"]?> - Messaging</div>

<div class="container">
    <!-- Sidebar Users -->
    <div class="sidebar" id="userList"></div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="messages" id="messages"></div>

        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
let receiver_id = 0;

/* Load user list */
function loadUsers() {
    fetch("messages.php", {
        method: "POST",
        body: new URLSearchParams({ get_users: 1 })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("userList").innerHTML = "";
        data.forEach(user => {
            document.getElementById("userList").innerHTML += `
                <div class='user-item' onclick='openChat(${user.id})'>
                    <b>${user.username}</b><br>
                    <small>${user.role}</small>
                </div>
            `;
        });
    });
}

function openChat(id) {
    receiver_id = id;
    loadMessages();
}

/* Load messages */
function loadMessages() {
    if (receiver_id == 0) return;

    fetch("messages.php", {
        method: "POST",
        body: new URLSearchParams({ 
            fetch_messages: 1,
            receiver_id: receiver_id 
        })
    })
    .then(res => res.json())
    .then(data => {
        let msgBox = document.getElementById("messages");
        msgBox.innerHTML = "";

        data.forEach(m => {
            let cls = m.sender_id == <?= $_SESSION["user_id"] ?> ? "sent" : "received";
            msgBox.innerHTML += `<div class='msg ${cls}'>${m.message}</div>`;
        });

        msgBox.scrollTop = msgBox.scrollHeight;
    });
}

/* Send message */
function sendMessage() {
    let text = document.getElementById("messageInput").value;
    if (text === "" || receiver_id === 0) return;

    fetch("messages.php", {
        method: "POST",
        body: new URLSearchParams({
            send_message: 1,
            receiver_id: receiver_id,
            message: text
        })
    });

    document.getElementById("messageInput").value = "";
    loadMessages();
}

setInterval(loadMessages, 1500);
loadUsers();
</script>

</body>
</html>
