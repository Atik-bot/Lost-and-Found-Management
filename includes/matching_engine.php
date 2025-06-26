<?php
// Function to find and notify users of potential matches
function findAndNotifyMatches($conn, $newItem) {
    // Determine the opposite status to search for
    $oppositeStatus = ($newItem['status'] == 'lost') ? 'found' : 'lost';
    
    
    
    $stmt = $conn->prepare("SELECT i.*, u.email as user_email, u.id as user_id_to_notify FROM items i JOIN users u ON i.user_id = u.id WHERE i.status = ? AND i.category = ? AND i.location = ? AND i.id != ?");
    $stmt->bind_param("sssi", $oppositeStatus, $newItem['category'], $newItem['location'], $newItem['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($match = $result->fetch_assoc()) {
        // We found a potential match!
        
        // 1. Create a notification in the database for the user who owns the matched item
        $notification_message = "A potential match for your " . $oppositeStatus . " item '" . htmlspecialchars($match['item_name']) . "' has been found!";
        $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, item_id, matched_item_id, message) VALUES (?, ?, ?, ?)");
        $notifStmt->bind_param("iiis", $match['user_id_to_notify'], $match['id'], $newItem['id'], $notification_message);
        $notifStmt->execute();
        $notifStmt->close();

        // 2. Send an email notification
        // Note: For a real application, using a library like PHPMailer is recommended.
        // The mail() function requires a configured mail server (like sendmail) to work.
        $to = $match['user_email'];
        $subject = 'Potential Match Found for Your Item!';
        $body = "Hello,\n\nA potential match has been found for your item: '" . htmlspecialchars($match['item_name']) . "'.\n";
        $body .= "A new item was reported that matches its category and location.\n";
        $body .= "Please log in to your dashboard to review the details.\n\nThank you,\nThe Lost & Found Team";
        $headers = 'From: no-reply@lostandfound.com' . "\r\n" .
                   'Reply-To: no-reply@lostandfound.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // Uncomment the line below to enable email sending.
        // mail($to, $subject, $body, $headers);
    }

    $stmt->close();
}
?>