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
    define('DB_FILE', dirname(__FILE__) . '/db.sqlite');
    define('UPLOADS_PATH', 'uploads/');
    define('CONTACT_EMAIL', 'test@example.com');

    // DB connection

    $connection = new SQLite3(DB_FILE, SQLITE3_OPEN_READWRITE);    

    // - Dont touch here

    // Query functions

    function getRows($sql) {
        global $connection;
        $query = $connection->query($sql);
        while ($row = $query->fetchArray()){
            $data[] = $row;
        }
        return $data;
    }

    // - To get all pages contents, call function without parameters.
    // - If you want a list of pages, pass as parameter a piece of page name (page name is defined on backend).
    // - To get a specific page, pass as parameter the page full name.
    // - These instructions are also applied to files.

    function getPages($name = ''){
        $sql = "SELECT * FROM pages";
        if (!empty($name)) $sql .= " WHERE name LIKE '%{$name}%'";
        $sql .= " ORDER BY name";
        return getRows($sql);
    }

    function getFiles($name = ''){
        $sql = "SELECT * FROM files";
        if (!empty($pages)) $sql .= " WHERE name LIKE '%{$name}%'";
        $sql .= " ORDER BY name";
        return getRows($sql);
    }

    // - Here a contact form function. This must send a mail to address defined on configs.
    // - Must receive as parameter a array with these fields: 'name', 'telephone', 'email', 'subject', 'message'.
    // - The returned value is a array with two fields: status, message. The status value is success or danger.
    // - The message is a custom string which can be modified on contactForm function body.

    // Contact form function

    function contactForm($data){

        // To be implemented...

    }

    // DB connection close

?>
