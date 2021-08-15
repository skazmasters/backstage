<?php

add_action('wp_ajax_contact', 'my_ajax_contact');
add_action('wp_ajax_nopriv_contact', 'my_ajax_contact');

function my_ajax_contact()
{
    $success = false;
    $error = null;

    $data = file_get_contents("php://input");
    $request = json_decode($data, true);

    $name = isset($request['name']) ? $request['name'] : null;
    $email = isset($request['email']) ? $request['email'] : null;
    $subject = isset($request['subject']) ? $request['subject'] : null;
    $message = isset($request['message']) ? $request['message'] : null;

    $contactsPage = \App\getPageByTemplate('page-contacts');
    if (!$contactsPage) {
        $error = 'Contact page not found';
    } else {
        $recipient = get_field('form_submission_email', $contactsPage);
        if (empty($recipient)) {
            $error = 'Empty recipient';
        } else {
            $fields = [
                'Name' => $name,
                'E-Mail' => $email,
                'Subject' => $subject,
                'Message' => $message
            ];

            foreach ($fields as $field => $value) {
                $message .= '<p><b>' . $field . ':</b> ' . $value . '</p>';
            }

            if (!wp_mail($recipient, 'Request from website Contacts page', $message, [
                'Content-type: text/html; charset=utf-8'
            ])) {
                $error = 'Error sending mail';
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'error' => $error
    ]);
    die;
}