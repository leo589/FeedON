(function() {
    let chatOpen = false;
    let lastMessageId = 0;

    // Toggle the chat window when the header is clicked
    $('#live-chat header').on('click', function() {
        $('.chat').slideToggle(300, 'swing', function() {
            chatOpen = !chatOpen;
            if (chatOpen) {
                $('.chat-message-counter').fadeOut(300);
            }
        });
    });

    // Close the chat window when the close button is clicked
    $('.chat-close').on('click', function(e) {
        e.preventDefault();
        $('#live-chat').fadeOut(300);
    });

    // Load chat messages
    function loadMessages() {
        $.ajax({
            url: 'load_messages.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const messages = data.messages;
                let newMessages = false;

                $('#chat-history').html('');
                messages.forEach(function(message) {
                    const messageHtml = `
                        <div class="chat-message clearfix">
                            <img src="http://gravatar.com/avatar/${md5(message.username)}?s=32" alt="" width="32" height="32">
                            <div class="chat-message-content clearfix">
                                <span class="chat-time">${message.time}</span>
                                <h5>${message.username}</h5>
                                <p>${message.text}</p>
                            </div>
                        </div>
                        <hr>
                    `;
                    $('#chat-history').append(messageHtml);

                    if (message.id > lastMessageId) {
                        lastMessageId = message.id;
                        newMessages = true;
                    }
                });

                if (!chatOpen && newMessages) {
                    const counter = parseInt($('.chat-message-counter').text()) + 1;
                    $('.chat-message-counter').text(counter).fadeIn(300);
                }
            }
        });
    }

    // Send chat message
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        const message = $('#message').val();

        $.ajax({
            url: 'send_message.php',
            method: 'POST',
            dataType: 'json',
            data: { message: message },
            success: function(data) {
                if (data.status === 'success') {
                    $('#message').val('');
                    loadMessages();
                } else {
                    console.error('Error sending message:', data.error);
                }
            }
        });
    });

    // Load messages initially and set interval to refresh every second
    loadMessages();
    setInterval(loadMessages, 1000);
})();
function md5(string) {
    return CryptoJS.MD5(string).toString();
}
