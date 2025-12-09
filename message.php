<?php
include("DB.php");
session_start();

// Restrict access to logged-in applicants
if(!isset($_SESSION['applicant_id'])){
    die("Access denied. You must be logged in as an applicant.");
}

$applicant_id = $_SESSION['applicant_id'];

/* --------------------
   SEND MESSAGE
-------------------- */
if(isset($_POST['send_message'])){
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    if($receiver_id > 0 && !empty($message)){
        $stmt = $conn->prepare("INSERT INTO messages (sender_type,sender_id,receiver_type,receiver_id,message_text) VALUES (?,?,?,?,?)");
        $sender_type = 'applicant';
        $receiver_type = 'admin'; // sending to admin/org
        $stmt->bind_param("siisi", $sender_type, $applicant_id, $receiver_type, $receiver_id, $message);

        if($stmt->execute()){
            echo "Message sent successfully";
        } else {
            echo "Error: ".$stmt->error;
        }
    } else {
        echo "Message empty or invalid receiver";
    }
    exit();
}

/* --------------------
   FETCH MESSAGES
-------------------- */
if(isset($_POST['fetch_messages'])){
    $receiver_id = intval($_POST['receiver_id']); // admin id

    $stmt = $conn->prepare("
        SELECT * FROM messages 
        WHERE (sender_type='applicant' AND sender_id=? AND receiver_type='admin' AND receiver_id=?)
           OR (sender_type='admin' AND sender_id=? AND receiver_type='applicant' AND receiver_id=?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param("iiii", $applicant_id, $receiver_id, $receiver_id, $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($messages ?: []);
    exit();
}

/* --------------------
   GET ADMIN USERS (only 1 in your system)
-------------------- */
if(isset($_POST['get_users'])){
    $result = $conn->query("SELECT id, name FROM organisations LIMIT 1");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cameroon JobPortal 🇨🇲 - Messaging</title>
<style>
body { font-family: 'Poppins', sans-serif; background:#f5f6fa; margin:0; }
header { background:#007A3D; color:white; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; }
header h1 { font-size:22px; }
header nav a { color:white; margin-left:15px; text-decoration:none; font-weight:bold; }
header nav a:hover { text-decoration:underline; }

.container { display:flex; height:calc(100vh - 70px); margin-top:0; }
.chat-sidebar { width:25%; background:white; overflow-y:auto; border-right:2px solid #ddd; }
.user-item { padding:12px; border-bottom:1px solid #eee; cursor:pointer; }
.user-item:hover { background:#f0f8ff; }
.chat-area { flex:1; display:flex; flex-direction:column; background:#fafafa; }
.messages { flex:1; padding:20px; overflow-y:auto; }
.msg { padding:10px; border-radius:10px; margin-bottom:10px; max-width:60%; word-wrap: break-word; }
.sent { background:#ffce00; align-self:flex-end; }
.received { background:white; align-self:flex-start; }
.input-area { display:flex; padding:10px; background:white; border-top:2px solid #ddd; gap:5px; }
input { flex:1; padding:10px; font-size:16px; }
button { padding:10px 20px; background:#007A3D; color:white; border:none; cursor:pointer; }
button:hover { background:#005e2d; }
.no-messages { color:#777; text-align:center; margin-top:20px; }
</style>
</head>
<body>

<header>
    <h1>Cameroon JobPortal 🇨🇲</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="jobs.php">Jobs</a>
        <a href="applicant_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <!-- Sidebar: Admin List -->
    <div class="chat-sidebar" id="userList">
        <p style="padding:10px;color:#777;">Loading admin...</p>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="messages" id="messages">
            <p class="no-messages">Select admin to view messages</p>
        </div>
        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
let receiver_id = 0;

// Load admin
function loadUsers(){
    fetch("applicantmessage.php",{method:"POST",body:new URLSearchParams({get_users:1})})
    .then(res=>res.json())
    .then(data=>{
        let list = document.getElementById("userList");
        list.innerHTML="";
        if(data.length===0){
            list.innerHTML = "<p style='padding:10px;color:#777;'>No admin found</p>";
            return;
        }
        data.forEach(u=>{
            let div = document.createElement("div");
            div.className="user-item";
            div.innerText=u.name;
            div.onclick = ()=>openChat(u.id);
            list.appendChild(div);
        });
    });
}

// Open chat
function openChat(id){
    receiver_id = id;
    document.getElementById("messages").innerHTML="<p class='no-messages'>Loading messages...</p>";
    loadMessages();
}

// Load messages
function loadMessages(){
    if(receiver_id===0) return;
    fetch("applicantmessage.php",{method:"POST",body:new URLSearchParams({fetch_messages:1, receiver_id:receiver_id})})
    .then(res=>res.json())
    .then(data=>{
        let msgBox = document.getElementById("messages");
        msgBox.innerHTML="";
        if(data.length===0){
            msgBox.innerHTML="<p class='no-messages'>No messages yet. Start the conversation!</p>";
            return;
        }
        data.forEach(m=>{
            let cls = m.sender_type==='applicant'?"sent":"received";
            let div = document.createElement("div");
            div.className="msg "+cls;
            div.innerText=m.message_text;
            msgBox.appendChild(div);
        });
        msgBox.scrollTop = msgBox.scrollHeight;
    });
}

// Send message
function sendMessage(){
    let text = document.getElementById("messageInput").value.trim();
    if(text==="" || receiver_id===0) return;

    fetch("applicantmessage.php",{method:"POST",body:new URLSearchParams({send_message:1, receiver_id:receiver_id, message:text})})
    .then(res=>res.text())
    .then(data=>{
        console.log("Server response:",data);
        document.getElementById("messageInput").value="";
        loadMessages();
    })
    .catch(err => console.error(err));
}

// Auto-refresh messages every 1.5s
setInterval(loadMessages,1500);
loadUsers();
</script>

</body>
</html>
