<?php

namespace ultraphp\core;

/**
 * Error class
 * Manages errors
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @copyright (c) 2018, Neeraj Mourya
 * @license https://opensource.org/licenses/MIT MIT
 * @since 2.0.0
 */
class Error {

    /**
     * Handle Errors
     * @param string $error_level
     * @param string $error_message
     * @param string $error_file
     * @param string $error_line
     * @param string $error_context
     */
    public static function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context) {
//        $error = "lvl: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | ln:" . $error_line;
        $error = array(
            'level' => $error_level,
            'message' => $error_message,
            'file' => $error_file,
            'line' => $error_line,
            'context' => $error_context
        );
        switch ($error_level) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_PARSE:
                self::errorLog($error, "FATAL");
                break;
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                self::errorLog($error, "ERROR");
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                self::errorLog($error, "WARN");
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                self::errorLog($error, "INFO");
                break;
            case E_STRICT:
                self::errorLog($error, "DEBUG");
                break;
            default:
                self::errorLog($error, "WARN");
        }
    }

    /**
     * Handle Shutdown errors
     */
    public static function shutdownHandler() { //will be called when php script ends.
        $lasterror = error_get_last();
        switch ($lasterror['type']) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_PARSE:
//                $error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
                $error = array(
                    'level' => $lasterror['type'],
                    'message' => $lasterror['message'],
                    'file' => $lasterror['file'],
                    'line' => $lasterror['line'],
                    'context' => ''
                );
                self::errorLog($error, "FATAL");
        }
    }

    /**
     * Returns the exploded message array
     * @param string $error_message
     * @return array
     */
    public static function explodedMessage($error_message) {
        $message_arr = array();
        $message_arr['message'] = self::strbefore($error_message, "Stack trace:");
        $stacktrace = self::strafter($error_message, "Stack trace:");
        $message_arr['stack_trace'] = explode("#", trim(self::strafter($error_message, "Stack trace:"), "#"));
        unset($message_arr['stack_trace'][0]);
        return $message_arr;
    }

    /**
     * Returns a substring after a substring from a string
     * @param string $string
     * @param string $substring
     * @return string
     */
    public static function strafter($string, $substring) {
        $pos = strpos($string, $substring);
        if ($pos === false)
            return $string;
        else
            return(substr($string, $pos + strlen($substring)));
    }

    /**
     * Returns a substring before a substring from a string
     * @param string $string
     * @param string $substring
     * @return string
     */
    public static function strbefore($string, $substring) {
        $pos = strpos($string, $substring);
        if ($pos === false)
            return $string;
        else
            return(substr($string, 0, $pos));
    }

    /**
     * print the errors
     * @param array $error
     * @param int $errlvl
     */
    public static function errorLog($error, $errlvl) {
        $expMessage = self::explodedMessage($error['message']);
        ?>
        <!DOCTYPE HTML>
        <html>
            <head>
                <title><?php echo $errlvl . " | " . $expMessage['message']; ?></title>
                <style>
                    html{
                        min-height:100%;
                    }
                    body{
                        min-height: 100%;
                        margin: 0px;
                        color: rgba(255,255,255,0.5);
                        font-size: 16px;
                        line-height: 1.6;
                        font-family: monospace;

                        background: #2b5876;  /* fallback for old browsers */
                        background: -webkit-linear-gradient(to bottom, #4e4376, #2b5876);  /* Chrome 10-25, Safari 5.1-6 */
                        background: linear-gradient(to bottom, #4e4376, #2b5876); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

                    }
                    body *{
                        position:relative;
                        box-sizing: border-box;

                    }
                    .wrapper{           
                        width: 95%;
                        min-height: 100%;
                        display: table;
                        margin: 0 auto;
                        margin-top: 25px;
                        margin-bottom: 25px;
                        border: 1px solid rgba(255,255,255,0.1);
                        background-color: rgba(0,0,0,0.1);
                    }
                    .left, .right{
                        display:table-cell;
                        vertical-align:top;
                    }
                    .left{
                        border-right: 1px solid rgba(255,255,255,0.1);
                        width:148px;
                        height:100%;
                        padding: 10px;
                    }
                    .right{
                    }
                    .error-header, .error-body{
                        padding:20px;
                    }
                    .error-header{
                        border-bottom: 1px solid rgba(255,255,255,0.1);
                    }
                    .error-table, .stacktrace{
                        width: 100%;                        
                    }
                    .error-table th,.error-table td, .stacktrace th, .stacktrace td{
                        text-align: left;
                        vertical-align: top;
                        padding: 6px 0px;
                    }

                    .error-table td, .stacktrace td{
                        padding-left: 5px;
                    }


                </style>
            </head>
            <body>
                <div class="wrapper">
                    <div class="left">
                        <img src="<?php echo Helper::url('/assets/images/logo.png'); ?>">
                    </div>
                    <div class="right">
                        <div class="error-header">
                            <h2><?php echo $errlvl; ?></h2>
                            <table class="error-table">
                                <tr>
                                    <th>Message</th>
                                    <td> : </td>
                                    <td><?php echo $expMessage['message']; ?></td>    
                                </tr>
                                <tr>
                                    <th>File</th>
                                    <td> : </td>
                                    <td><?php echo $error['file']; ?></td>    
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <td> : </td>
                                    <td><?php echo $error['line']; ?></td>    
                                </tr>

                            </table>
                        </div>
                        <div class="error-body">
                            <h3>Stacktrace</h3>
                            <table class="stacktrace">
                                <?php foreach ($expMessage['stack_trace'] as $st) : ?>
                                    <?php if (isset($st) && !empty($st)) : ?>
                                        <tr>
                                            <th class="line-number"><?php echo self::strbefore($st, " "); ?></th>
                                            <td><?php echo self::strafter($st, " "); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                </div>
            </body>
        </html>
        <?php
        die;
    }

}
?>