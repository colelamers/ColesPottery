<?php

namespace Website\admin;
use \DateTime;
use \PDO;

class CommonFunctions
{
    /**
    *@param string | clientVar | Name of the variable you want for the client to use
    *@param string | serverVar | Value from the server that is passed to the client
    *@return void | Html script tags injected at the called location
    *@example :
    * use \Website\admin\CommonFunctions;
    * $temp = 'serverVarAsString';
    * echo $temp;
    * echo CommonFunctions::PassVariableToClient("jsVarAsString", $temp);
    */
    public static function ServerToClientVar(string $clientVar,
    string $serverVar ):void
    {
        $passVar = '<script>';
        $passVar .= 'let ' . $clientVar . ' = "' . $serverVar . '";';
        $passVar .= '</script>';
        echo $passVar;
    }

    // TODO: --3-- look into why this is here and also in the setup page
    /**
    *@return void  initializes the ErrorLog for the page
    */
    public static function InitializeErrorLog()
    {
        ini_set('display_errors', 0);// hides errors on screen so they don't show
        ini_set('log_errors', 1);
        $now = new DateTime;
        ini_set('error_log', dirname(__FILE__) . '/logs/' . $now->format("Ymd")
        . 'error.log');
        error_reporting(E_ALL);
    }

    /**
    * @param string | sqlQuery | TODO: --3-- make example | Contains the query for the function
    * @param dictionary | sqlParams | TODO: --3-- make example | Contains the params that need to be washed
    * @return
    */
    public static function GetSqlBoundParams(string $sqlQuery, Array $sqlParams)
    {
        // Establish the sql connection
        $host_name = 'db5006267897.hosting-data.io';
        $database = 'dbs5235579';
        $user_name = 'dbu2765816';
        $password = 'm0zIYieIqcCzJpBIUQ9Y';
        $conn = NULL;

        try
        {
            // Setup connection to sql
            $conn = new PDO("mysql:host=$host_name; dbname=$database;",
            $user_name, $password);

            $results = $conn->prepare($sqlQuery);
            $results->execute($sqlParams);
            return $results->fetchAll();
        } // try
        catch (\PDOException $e)
        {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return null;
    } // function GetSqlBoundParams



    // TODO: --1-- this is older code i was using and is kinda bad/prone. Revise to use GetSqlBoundParams instead
    /**
    * @param string | sqlQuery | TODO: --3-- make example | Contains the query for the function
    * @param dictionary | sqlParams | TODO: --3-- make example | Contains the params that need to be washed
    * @return
    */
    public static function GetSqlResults(string $sqlQuery, Array $sqlParams)
    {
        // Establish the sql connection
        $host_name = 'db5006267897.hosting-data.io';
        $database = 'dbs5235579';
        $user_name = 'dbu2765816';
        $password = 'm0zIYieIqcCzJpBIUQ9Y';
        $conn = NULL;

        try
        {
            // Setup connection to sql
            $conn = new PDO("mysql:host=$host_name; dbname=$database;",
            $user_name, $password);

            // Swap Keys with values. Should only be strings.
            foreach ($sqlParams as $key => $value)
            {
                // Take the key, swap out with value into query
                $jsonWash = json_encode($value);

                if (strpos($key, ':') !== false)
                {
                    $jsonWash = CommonFunctions::SanitizeSqlParam($jsonWash, false);
                }
                else
                {
                    $jsonWash = CommonFunctions::SanitizeSqlParam($jsonWash, true);
                }

                $sqlQuery = str_replace($key, $jsonWash, $sqlQuery);
            } // foreach

            $results = $conn->prepare($sqlQuery);
            $results->execute();
            return $results->fetchAll();
        } // try
        catch (\PDOException $e)
        {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return null;
    } // function GetSqlResults

    /**
    * @param json_encode | $sqlParam | Forces a string in an json_ecoded string
    * to have any oddities removed and shoved back into json, then stripped
    * stripped of the stringing if fed the parameter.
    *
    * @option | If the param key contains :, then place as is. If it contains @
    * then place it as a string with quotes surrounding it.
    */
    public static function SanitizeSqlParam(string $sqlParam, bool $stringIt)
    {
        $decoded = json_decode($sqlParam);
        $decoded = str_replace('\"', '', $decoded);
        $decoded = str_replace('\'', '', $decoded);
        $decoded = json_encode($decoded);
        $getString = '';
        if ($stringIt)
        {
            $getString .= '\'';
        }
        // 1 and -1 for json quotes
        for ($i = 1; $i < strlen($decoded) - 1; ++$i)
        {
            $getString .= $decoded[$i];
        }

        if ($stringIt)
        {
            $getString .= '\'';
        }

        return $getString;
    }
}
