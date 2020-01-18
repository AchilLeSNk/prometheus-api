<?php


namespace core;

class Error
{

    const LOG_FILE = __DIR__ . '/../log/error.log';

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (getenv('SHOW_ERRORS')) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        }

        if (getenv('LOG_ERRORS')) {
            $message = "Uncaught exception: '" . get_class($exception) . "' ";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= " Stack trace: " . $exception->getTraceAsString();
            $message .= " Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "\n";
            static::write_log($message);
        }
    }

    /**
     * Write log
     *
     * @param $message
     */
    public static function write_log($message)
    {
        file_put_contents(self::LOG_FILE, date("Y-m-d H:i:s") . ": " . $message, FILE_APPEND);
    }
}