<?php
    include_once 'constants.php';
    include_once 'db_connect.php';

    function finalized($uid, $mysqli)
    {
        $query = "SELECT finalized FROM fertig WHERE uid = '$uid'";
        $result = $mysqli->query($query);
        if ($result->num_rows >= 1) {
            return true;
        } else {
            return false;
        }
    }
    function error($type, $code, $msg)
    {
        echo json_encode($error = [
            'error' => $type,
            'code' => $code,
            'message' => $msg,
        ], JSON_PRETTY_PRINT);
    }
    function success($args)
    {
        $array = ['success' => true];
        foreach ($args as $k => $e) {
            $array[$k] = $e;
        }
        echo json_encode($array, JSON_PRETTY_PRINT);
    }
    function requestSent($from, $to, $mysqli)
    {
        if ($from !== $to) {
            $result = $mysqli->query("SELECT von, an FROM anfragen WHERE von = '$from' AND an = '$to' LIMIT 1");
            if ($result->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    function isUser($uid, $mysqli)
    {
        return isInvited($uid, $mysqli) || userExists($uid, $mysqli);
    }
    function isInvited($uid, $mysqli)
    {
        if ($stmt = $mysqli->prepare('SELECT id FROM ausstehende_einladungen WHERE uid = ? LIMIT 1')) {
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                return true;
            } elseif ($stmt->num_rows == 0) {
                return false;
            } else {
                echo(new Exception('Possible dublicate! Please fix the database'))->getMessage();
            }
        } else {
            echo(new Exception('Error on SQL Request : '.$mysqli->error, $mysqli->errno))->getMessage();
        }
    }
    function userExists($uid, $mysqli)
    {
        if ($stmt = $mysqli->prepare('SELECT id FROM person WHERE uid = ? LIMIT 1')) {
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                return true;
            } elseif ($stmt->num_rows == 0) {
                return isInvited($uid, $mysqli);
            } else {
                echo(new Exception('Possible dublicate! Please fix the database'))->getMessage();
            }
        } else {
            echo(new Exception('Error on SQL Request : '.$mysqli->error, $mysqli->errno))->getMessage();
        }

        return;
    }
    function getUser($username, $mysqli)
    {
        $query = "SELECT * FROM person INNER JOIN login ON person.uid = login.uid WHERE person.uid = '{$username}'";
        $result = $mysqli->query($query);
        if ($result != false) {
            $return = $result->fetch_assoc();
            $return['vorname'] = explode(' ', $return['name'])[0];
            $return['freunde'] = getFriends($username, $mysqli);
            $return['uid'] = $username;
            $return['freundesanzahl'] = count($return['freunde']);

            return $return;
        } else {
            echo null;
        }
    }
    function getFriends($username, $mysqli)
    {
        if ($stmt = $mysqli->prepare('SELECT friend FROM freunde WHERE uid = ? ORDER BY friendsSince ASC')) {
            $friends = [];
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($friend);
            $stmt->store_result();
            while ($stmt->fetch()) {
                $friends[] = $friend;
            }

            return $friends;
        } else {
            return array();
        }
    }
    function getMinimalUser($username, $mysqli)
    {
        $result = $mysqli->query("SELECT name, uid, directory FROM person WHERE uid = '$username'");
        if ($result != false) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    function isFriendsWith($user, $friend, $mysqli)
    {
        if ($friend != null && $user != null) {
            if ($user == $friend) {
                // Vereinbarung: Man ist mit sich selbst "befreundet" - erspart pro benutzer ein feld in der datenbank bzw. einen komplizierteren query
                return true;
            } else {
                $stmt = $mysqli->prepare('SELECT * FROM freunde WHERE uid = ? AND (freund1 = ? OR freund2 = ? OR freund3 = ? OR freund4 = ? OR freund5 = ?) LIMIT 1');
                $stmt->bind_param('ssssss', $user, $friend, $friend, $friend, $friend, $friend);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 1) { // genau eine Zeile gefunden, in der der freund und der benutzer vorkommen
                    return true;
                }
            }
        }

        return false;
    }
    function get_nav($active = ''){
        echo '<nav class="navigation mdl-navigation mdl-color--blue-grey-800" data-active="#' . $active . '">
            <a class="mdl-navigation__link" id="home" href="/admin/"><i class="material-icons">home</i><span>Admin-Bereich</span></a>
            <a class="mdl-navigation__link" id="manage-users" href="/admin/manage-users/"><i class="material-icons">view_headline</i><span>Benutzer verwalten</span></a>
            <a class="mdl-navigation__link" id="register-user" href="/admin/register-user/"><i class="material-icons">playlist_add</i><span>Benutzer einladen</span></a>
            <a class="mdl-navigation__link" id="manage-invitations" href="/admin/manage-invitations/"><i class="material-icons">dns</i><span>Einladungen verwalten</span></a>
            <a class="mdl-navigation__link" id="manage-questions" href="/admin/manage-questions/"><i class="material-icons">question_answer</i><span>Fragen verwalten</span></a>
            <a class="mdl-navigation__link" href="/admin/logout/"><i class="material-icons">exit_to_app</i><span>Abmelden</span></a>
            <div class="mdl-layout-spacer"></div>
            <a class="mdl-navigation__link" id="support" href="/support/"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
        </nav>';
        echo '<script>var active = $("nav").attr("data-active"); $("a" + active).addClass("active");</script>';
    }
    function secure_session_start()
    {
        //print_r($_SESSION);
        $session_name = 'sid';   // vergib einen Sessionnamen
        $secure = SECURE;
        // Damit wird verhindert, dass JavaScript auf die session id zugreifen kann.
        $httponly = true;
        // Holt Cookie-Parameter.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams['lifetime'],
                $cookieParams['path'],
                $cookieParams['domain'],
                $secure,
                $httponly);
        // Setzt den Session-Name zu oben angegebenem.
        session_name($session_name);
        session_start(); // Startet die PHP-Sitzung
        //session_regenerate_id();    // Erneuert die Session, löscht die alte.
    }
    function login($username, $password, $mysqli)
    {
        if ($stmt = $mysqli->prepare('SELECT * FROM admin_login WHERE uid = ?')) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $user, $db_password);
            $stmt->fetch();

            $password = sha1($password);
            if ($stmt->num_rows == 1) {
                if ($db_password == $password) {
                    secure_session_start();
                    //$user_browser = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = sha1($password.$username);

                    return true;
                }
            }
        }

        return false;
    }
    function login_check($mysqli)
    {
        if (isset($_SESSION['id'], $_SESSION['username'], $_SESSION['login_string'])) {
            $user_id = $_SESSION['id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];
            //$user_browser = $_SERVER['HTTP_USER_AGENT'];
            if ($stmt = $mysqli->prepare('SELECT password FROM admin_login WHERE uid = ? LIMIT 1')) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    $login_check = sha1($password.$username);
                    if ($login_check == $login_string) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }

        return false;
    }
