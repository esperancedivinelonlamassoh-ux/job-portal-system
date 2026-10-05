<?php
include("DB.php");
session_start();

// Restrict access to admin/organization users
if(!isset($_SESSION['org_id'])){
    die("Access denied. You must be logged in as an admin.");
}

$admin_id = $_SESSION['org_id'];

/* --------------------
   SEND MESSAGE (ADMIN → APPLICANT)
-------------------- */
if(isset($_POST['send_message'])){
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    if($receiver_id > 0 && !empty($message)){
        $stmt = $conn->prepare("INSERT INTO messages (sender_type, sender_id, receiver_type, receiver_id, message_text) VALUES (?,?,?,?,?)");
        $sender_type = 'admin';
        $receiver_type = 'applicant';
        $stmt->bind_param("siisi", $sender_type, $admin_id, $receiver_type, $receiver_id, $message);

        if($stmt->execute()){
            echo "Message sent";
        } else {
            echo "Error: ".$stmt->error;
        }
    } else {
        echo "Message empty or invalid receiver";
    }
    exit();
}

/* --------------------
   FETCH CHAT messages for ADMIN
-------------------- */
if(isset($_POST['fetch_messages'])){
    $applicant_id = intval($_POST['receiver_id']);

    $stmt = $conn->prepare("
        SELECT * FROM messages 
        WHERE (sender_type='admin' AND sender_id=? AND receiver_type='applicant' AND receiver_id=?)
           OR (sender_type='applicant' AND sender_id=? AND receiver_type='admin' AND receiver_id=?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param("iiii", $admin_id, $applicant_id, $applicant_id, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($messages ?: []);
    exit();
}

/* --------------------
   GET ALL APPLICANTS
-------------------- */
if(isset($_POST['get_users'])){
    $result = $conn->query("SELECT id, first_name, last_name FROM applicants ORDER BY first_name ASC");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Messaging - Cameroon JobPortal 🇨🇲</title>
<style>
body { font-family: 'Poppins', sans-serif; background:#f6f8fa; margin:0; }
header { background:#007A3D; color:white; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; }
header h1 { font-size:22px; margin:0; }
header nav a { color:white; margin-left:15px; text-decoration:none; font-weight:bold; }
header nav a:hover { text-decoration:underline; }

.container { display:flex; height:calc(100vh - 70px); }
.chat-sidebar { width:25%; background:white; overflow-y:auto; border-right:2px solid #ddd; }
.user-item { padding:12px; border-bottom:1px solid #eee; cursor:pointer; }
.user-item:hover { background:#f0f8ff; }
.chat-area { flex:1; display:flex; flex-direction:column; background:#fafafa; }
.messages { flex:1; padding:20px; overflow-y:auto; }
.msg { padding:10px; border-radius:10px; margin-bottom:10px; max-width:60%; word-wrap: break-word; }
.sent { background:#007A3D; color:white; align-self:flex-end; }
.received { background:#ffce00; align-self:flex-start; }
.input-area { display:flex; padding:10px; background:white; border-top:2px solid #ddd; gap:5px; }
input { flex:1; padding:10px; font-size:16px; }
button { padding:10px 20px; background:#ce1126; color:white; border:none; cursor:pointer; }
button:hover { background:#a00c1e; }
.no-messages { color:#777; text-align:center; margin-top:20px; }
</style>
</head>
<body>

<header>
    <h1>Admin Messaging 🇨🇲 - Cameroon JobPortal</h1>
    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="post_job.php">Post Job</a>
        <a href="view_interview.php">Interviews</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <!-- Sidebar: Applicants List -->
    <div class="chat-sidebar" id="userList">
        <p style="padding:10px;color:#777;">Loading applicants...</p>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="messages" id="messages">
            <p class="no-messages">Select an applicant to start conversation</p>
        </div>

        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type your message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
let receiver_id = 0;

// Load applicant list
function loadUsers(){
    fetch("adminmessage.php",{method:"POST",body:new URLSearchParams({get_users:1})})
    .then(res=>res.json())
    .then(data=>{
        let list = document.getElementById("userList");
        list.innerHTML = "";
        if(data.length === 0){
            list.innerHTML = "<p style='padding:10px;color:#777;'>No applicants found</p>";
            return;
        }
        data.forEach(u => {
            let div = document.createElement("div");
            div.className="user-item";
            div.innerText = u.first_name + " " + u.last_name;
            div.onclick = ()=>openChat(u.id);
            list.appendChild(div);
        });
    });
}

// Open chat with applicant
function openChat(id){
    receiver_id = id;
    document.getElementById("messages").innerHTML="<p class='no-messages'>Loading messages...</p>";
    loadMessages();
}

// Load chat messages
function loadMessages(){
    if(receiver_id===0) return;
    fetch("adminmessage.php",{method:"POST",body:new URLSearchParams({fetch_messages:1, receiver_id:receiver_id})})
    .then(res=>res.json())
    .then(data=>{
        let msgBox = document.getElementById("messages");
        msgBox.innerHTML = "";
        if(data.length === 0){
            msgBox.innerHTML = "<p class='no-messages'>No messages yet</p>";
            return;
        }
        data.forEach(m => {
            let cls = m.sender_type === 'admin' ? "sent" : "received";
            let div = document.createElement("div");
            div.className = "msg " + cls;
            div.innerText = m.message_text;
            msgBox.appendChild(div);
        });
        msgBox.scrollTop = msgBox.scrollHeight;
    });
}

// Admin sends message
function sendMessage(){
    let text = document.getElementById("messageInput").value.trim();
    if(text === "" || receiver_id === 0) return;

    fetch("adminmessage.php",{method:"POST",body:new URLSearchParams({send_message:1, receiver_id:receiver_id, message:text})})
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("messageInput").value = "";
        loadMessages();
    });
}

setInterval(loadMessages,1500);
loadUsers();
</script>

</body>
</html>
