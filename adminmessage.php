<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['admin_id'])){
    die("You must be logged in as admin.");
}

$pdo = new PDO("mysql:host=localhost;dbname=job_portal", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ----------------------
// SEND MESSAGE
// ----------------------
if(isset($_POST['send_message'])){
    $stmt = $pdo->prepare("INSERT INTO messages (sender_type, sender_id, receiver_type, receiver_id, message_text) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        'admin',
        $_SESSION['admin_id'],
        'applicant',
        $_POST['receiver_id'],
        $_POST['message']
    ]);
    echo "success";
    exit();
}

// ----------------------
// FETCH MESSAGES
// ----------------------
if(isset($_POST['fetch_messages'])){
    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE (sender_type='admin' AND sender_id=? AND receiver_type='applicant' AND receiver_id=?)
           OR (sender_type='applicant' AND sender_id=? AND receiver_type='admin' AND receiver_id=?)
        ORDER BY created_at ASC
    ");
    $stmt->execute([
        $_SESSION['admin_id'], $_POST['receiver_id'],
        $_POST['receiver_id'], $_SESSION['admin_id']
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
    exit();
}

// ----------------------
// GET ALL APPLICANTS
// ----------------------
if(isset($_POST['get_users'])){
    $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM applicants");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Messaging</title>
<style>
body { margin:0; font-family:Arial; background:#f4f4f4; }
.header { background:#007000; color:white; padding:15px; text-align:center; font-size:24px; }
.container { display:flex; height:calc(100vh - 60px); }
.sidebar { width:25%; background:white; overflow-y:auto; border-right:2px solid #ddd; }
.user-item { padding:15px; border-bottom:1px solid #eee; cursor:pointer; }
.user-item:hover { background:#e8ffe8; }
.chat-area { width:75%; display:flex; flex-direction:column; background:#fafafa; }
.messages { flex:1; padding:20px; overflow-y:auto; }
.msg { padding:10px; border-radius:10px; margin-bottom:10px; max-width:60%; word-wrap: break-word; }
.sent { background:#ffce00; align-self:flex-end; }
.received { background:white; align-self:flex-start; }
.input-area { display:flex; padding:10px; background:white; border-top:2px solid #ddd; gap:5px; }
input { flex:1; padding:10px; font-size:16px; }
button { padding:10px 20px; background:#d10000; color:white; border:none; cursor:pointer; }
</style>
</head>
<body>

<div class="header">
    🇨🇲 Welcome Admin <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : ""; ?> - Messaging
</div>

<div class="container">
    <!-- Sidebar: Applicants List -->
    <div class="sidebar" id="userList"></div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="messages" id="messages"></div>
        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
let receiver_id = 0;

// Load applicants list
function loadUsers(){
    fetch("admin_messages.php", {
        method:"POST",
        body: new URLSearchParams({get_users:1})
    })
    .then(res=>res.json())
    .then(data=>{
        let list = document.getElementById("userList");
        list.innerHTML = "";
        data.forEach(app=>{
            list.innerHTML += `
                <div class='user-item' onclick='openChat(${app.id})'>
                    <b>${app.first_name} ${app.last_name}</b>
                </div>
            `;
        });
    });
}

// Open chat with applicant
function openChat(id){
    receiver_id = id;
    loadMessages();
}

// Load messages
function loadMessages(){
    if(receiver_id===0) return;
    fetch("admin_messages.php", {
        method:"POST",
        body: new URLSearchParams({
            fetch_messages:1,
            receiver_id:receiver_id
        })
    })
    .then(res=>res.json())
    .then(data=>{
        let msgBox = document.getElementById("messages");
        msgBox.innerHTML="";
        data.forEach(m=>{
            let cls = m.sender_type==='admin'?"sent":"received";
            msgBox.innerHTML += `<div class='msg ${cls}'>${m.message_text}</div>`;
        });
        msgBox.scrollTop = msgBox.scrollHeight;
    });
}

// Send message
function sendMessage(){
    let text = document.getElementById("messageInput").value.trim();
    if(text==="" || receiver_id===0) return;
    fetch("admin_messages.php", {
        method:"POST",
        body:new URLSearchParams({
            send_message:1,
            receiver_id:receiver_id,
            message:text
        })
    }).then(res=>res.text()).then(data=>console.log("Send status:",data));
    document.getElementById("messageInput").value="";
    loadMessages();
}

// Auto refresh
setInterval(loadMessages,1500);
loadUsers();
</script>

</body>
</html>
