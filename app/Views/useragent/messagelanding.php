<?= $this->extend('/shared/main'); ?>

<?php 

    $sessionUserID = session()->get('id_user');
?>

<?= $this->section('css') ?>
    <style>
        .mytruncate {
            max-width: 200px; 
            overflow: hidden; 
            white-space: nowrap;
        }

        body {
            background-color: rgb(245,245,250) !important;
        }

        .chat-container {
            position: relative;
            height: calc(100vh - 50px); 
            padding-top: 60px;
            padding-bottom: 100px;
            overflow-y: auto;

            scrollbar-width: none; 
            -ms-overflow-style: none;
        }

        .chat-container::-webkit-scrollbar {
            display: none;
        }

    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

   
    <div class="container">

        <nav class="navbar fixed-top bg-mylight shadow-sm border-bottom">
            <div class="px-3">
                <a href="/message">
                    <i class="fas fa-angle-left"></i>
                   
                </a>
                <span class="ms-3"><?= $senderfname ?>&nbsp;<?= $senderlname ?></span>
            </div>
        </nav>

        <div data-mdb-perfect-scrollbar-init class="chat-container">
            <?php 

                date_default_timezone_set('Asia/Manila');

                $pngPath = "/resources/images/profilepics/$sessionUserID.png";
                $jpgPath = "/resources/images/profilepics/$sessionUserID.jpg";

                $profilepic = file_exists(FCPATH . ltrim($pngPath, '/')) 
                    ? $pngPath 
                    : (file_exists(FCPATH . ltrim($jpgPath, '/')) 
                    ? $jpgPath 
                    : "/resources/images/default.png");   

                $pngPathConvoWith = "/resources/images/profilepics/$sender.png";
                $jpgPathConvoWith = "/resources/images/profilepics/$sender.jpg";

                $profilepicConvoWith = file_exists(FCPATH . ltrim($pngPathConvoWith, '/')) 
                    ? $pngPathConvoWith
                    : (file_exists(FCPATH . ltrim($jpgPathConvoWith, '/')) 
                    ? $jpgPathConvoWith
                    : "/resources/images/default.png");

                $today = date('Y-m-d');

                foreach ($Convo as $msg):
                
                    $created_at = strtotime($msg['created_at']);
                    $msgDate = date('Y-m-d', $created_at);
                    
                    if ($msgDate === $today) {
                        $displayTime = date('g:i A', $created_at);
                    } 
                    else {
                        $displayTime = date('F j \a\t g:i A', $created_at);
                    }

                    $isMe = ($msg['sender'] == $sessionUserID);  
            ?>

            <?php if (!$isMe): ?>
                <div class="d-flex flex-row justify-content-start">
                    <img src="<?= $profilepicConvoWith . '?t=' . time(); ?>" class="profilepic rounded-circle"
                        alt="avatar" style="width: 45px; height: 100%;">
                    <div>
                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-body-tertiary"><?= $msg['messagecontent'] ?></p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end"><?= $displayTime ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex flex-row justify-content-end">
                    <div>
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary"><?= $msg['messagecontent'] ?></p>
                        <p class="small me-3 mb-3 rounded-3 text-muted"><?= $displayTime ?></p>
                    </div>
                    <img src="<?= $profilepic . '?t=' . time(); ?>" class="profilepic rounded-circle" style="width: 45px; height: 100%;">
                
                </div>
            <?php endif; ?>

            <?php endforeach; ?>
            <div style="height: 550px;"></div>
        </div>
        
        <!-- message box -->
        <div class="fixed-bottom">
            <div class="text-muted d-flex justify-content-start align-items-center ps-1 pe-3 pt-3 pb-3 bg-mylight border shadow mx-1 rounded"
                style="margin-bottom: 65px;">
                <div>
                    <img src="<?= $profilepic . '?t=' . time(); ?>" style="width: 45px; height: 45px;" class="profilepic rounded-circle">
                </div>
                <input class="d-none" id="receiverID" value="<?= $sender; ?>">
                <input id="messageToSend" type="text" class="form-control form-control-lg mx-2 flex-grow-1" placeholder="Type message">
                <button id="sendMessage" type="button" class="btn btn-tertiary btn-lg ms-1">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
   
<?= $this->endSection() ?>

<?= $this->section('js') ?>

    <script>

        const sender = "<?= esc($sender) ?>";
        const userId = "<?= session()->get('id_user') ?>"; 

        $("#sendMessage").click(function() {

            const messageContent = $("#messageToSend").val();
            const receiverID = $("#receiverID").val();

            if (messageContent.trim() === "") {
                return; 
            }

            $.ajax({
                url: '/message/send', 
                method: 'POST',
                data: {
                    messageToSend: messageContent,
                    receiverID: receiverID,
                },
                success: function(response) {
                    if (response.success) {
                        $("#messageToSend").val("");
                        fetchMessages();
                    } 
                    else {
                        console.error("Error sending message.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });

        let isAtBottom = true;

        function fetchMessages() {

            const chatContainer = $('[data-mdb-perfect-scrollbar-init]');
            const currentScrollTop = chatContainer.scrollTop();
            const scrollHeight = chatContainer[0].scrollHeight;
            const clientHeight = chatContainer[0].clientHeight;

            isAtBottom = currentScrollTop + clientHeight >= scrollHeight - 50;

            $.ajax({
                url: '/message/fetch',
                method: 'GET',
                data: { sender: sender },
                dataType: 'json',
                success: function(data) {
                    let chatHtml = '';
                    $.each(data, function(index, msg) {
                        const isMe = msg.sender == userId;
                        // const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
                        const createdAt = new Date(msg.created_at);
                        const today = new Date();
                        const isToday = createdAt.toDateString() === today.toDateString();

                        let time;
                        if (isToday) {
                            time = createdAt.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
                        } 
                        else {
                            const options = { month: 'long', day: 'numeric' };
                            const formattedDate = createdAt.toLocaleDateString(undefined, options); // e.g., April 26
                            const formattedTime = createdAt.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
                            time = `${formattedDate} at ${formattedTime}`;
                        }

                        if (!isMe) {
                            chatHtml += `
                                <div class="d-flex flex-row justify-content-start">
                                    <img src="<?= $profilepicConvoWith . '?t=' . time(); ?>"
                                      style="width: 45px; height: 45px;"  class="profilepic rounded-circle">
                                    <div>
                                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-body-tertiary">${msg.messagecontent}</p>
                                        <p class="small ms-3 mb-3 text-muted float-end">${time}</p>
                                    </div>
                                </div>`;
                        } 
                        else {
                            chatHtml += `
                                <div class="d-flex flex-row justify-content-end">
                                    <div>
                                        <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">${msg.messagecontent}</p>
                                        <p class="small me-3 mb-3 text-muted">${time}</p>
                                    </div>
                                    <img src="<?= $profilepic . '?t=' . time(); ?>" style="width: 45px; height: 45px;" class="profilepic rounded-circle">
                                </div>`;
                        }
                    });

                    chatContainer.html(chatHtml);
                  
                    if (isAtBottom) {
                        chatContainer.scrollTop(chatContainer[0].scrollHeight);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }

        $(document).ready(function() {
            fetchMessages();
            setInterval(fetchMessages, 3000);
        });
    </script>
    
    // <script>
    //     document.addEventListener('DOMContentLoaded', function() {
    //         const messageBox = document.querySelector('.fixed-bottom');
    //         const input = document.getElementById('messageToSend');
        
    //         input.addEventListener('focus', function() {
    //             const estimatedKeyboardHeight = window.innerHeight * 0.275;
    //             messageBox.style.transform = `translateY(-${estimatedKeyboardHeight}px)`;
    //         });

        
    //         input.addEventListener('blur', function() {
    //             // Reset position when keyboard is hidden
    //             messageBox.style.transform = 'translateY(0)';
    //         });
    //     });
    // </script>

<?= $this->endSection() ?>