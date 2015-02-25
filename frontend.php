<?php

    /**
     *
     * The MIT License (MIT)
     *
     * Copyright (c) 2015 Angelito M. Goulart
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in all
     * copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
     * SOFTWARE.
     */
    
    // - Hello, I'm DirtyCMS :)

    // Configs

    // - Here are general configs. You can change these configs.

    error_reporting(0);
    define('DIRTYCMS_DB_FILE', dirname(__FILE__) . '/db.sqlite');
    define('DIRTYCMS_UPLOADS_PATH', dirname(__FILE__) . '/uploads/');
    define('DIRTYCMS_FROM_EMAIL', 'yourname@yourdomain.com'); 
    define('DIRTYCMS_TO_EMAIL', 'yourname@yourdomain.com');

    // - Dont touch here

    // Query functions

    function DirtyCMS_getRows($sql) {
        $connection = new SQLite3(DIRTYCMS_DB_FILE, SQLITE3_OPEN_READWRITE);
        $query = $connection->query($sql);
        while ($row = $query->fetchArray()){
            $data[] = $row;
        }
        $connection->close();
        return $data;
    }

    // - To get all pages contents, call function without parameters.
    // - If you want a list of pages, pass as parameter a piece of page name (page name is defined on backend).
    // - To get a specific page, pass as parameter the page full name.
    // - These instructions are also applied to files.

    function DirtyCMS_getPages($name = ''){
        $sql = "SELECT * FROM pages";
        if (!empty($name)) $sql .= " WHERE name LIKE '%{$name}%'";
        $sql .= " ORDER BY name";
        return DirtyCMS_getRows($sql);
    }

    function DirtyCMS_getFiles($name = ''){
        $sql = "SELECT * FROM files";
        if (!empty($pages)) $sql .= " WHERE name LIKE '%{$name}%'";
        $sql .= " ORDER BY name";
        return DirtyCMS_getRows($sql);
    }

    // - Here a contact form function. This must send a mail to address defined on configs.
    // - Must receive as parameter a array with these fields: 'name', 'phone', 'email', 'subject', 'message'.
    // - The returned value is a array with two fields: status, message. The status value is success or danger.
    // - The message is a custom string which can be modified on DirtyCMS_contactForm function body.

    // Contact form function

    function DirtyCMS_contactForm($data){

        $name    = (isset($data['name']))    ? filter_var($data['name'], FILTER_SANITIZE_STRING)    : '';
        $phone   = (isset($data['phone']))   ? filter_var($data['phone'], FILTER_SANITIZE_STRING)   : '';
        $email   = (isset($data['email']))   ? filter_var($data['email'], FILTER_SANITIZE_EMAIL)    : '';
        $subject = (isset($data['subject'])) ? filter_var($data['subject'], FILTER_SANITIZE_STRING) : '';
        $message = (isset($data['message'])) ? filter_var($data['message'], FILTER_SANITIZE_STRING) : '';

        if (empty($name) || empty($phone) || empty($email) || empty($message)){
            $status['type'] = 'danger';
            $status['message'] = 'Fill all fields correctly.';
            return $status;
        }

        $headers =  "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/plain; charset=utf-8\n";
        $headers .= "Return-Path: " . DIRTYCMS_FROM_EMAIL . "\r\n";
        $headers .= "From: " . DIRTYCMS_FROM_EMAIL . "\r\n";
        $headers .= "To: " . DIRTYCMS_TO_EMAIL . "\r\n";
        $headers .= "Reply-To: {$email}\r\n";

        $body  = "-- {$subject} --\r\n\r\n";
        $body .= "{$name}\r\n";
        $body .= "{$phone}\r\n";
        $body .= "{$email}\r\n";
        $body .= "\r\n{$message}\r\n";
        
        if (mail(DIRTYCMS_TO_EMAIL, $subject, $body, $headers)) {
            $status['type'] = 'success';
            $status['message'] = 'Message sent successfully.';
        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while sending message. Try again later.';
        }

        return $status;

    }

?>