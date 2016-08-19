<?php

    /**
     * functions.php
     *
     * Computer Science 50
     * Modified for Final Project
     *
     * Helper functions.
     */

    require_once("constants.php");
    require_once("OAuth.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("apology.php", ["title" => "Error", "message" => $message]);
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("templates/header.php");

            // render template
            require("templates/$template");

            // render footer
            require("templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

   /**
     *  "OAuth" Yelp API request function for use with search()
     *   Based on example provided by Yelp here: 
     *   https://github.com/Yelp/yelp-api/blob/master/v2/php/sample.php 
     */
    function request($host, $path) {
    
        // set var with Yelp API host and user-defined GET method path
        $unsigned_url = "http://" . $host . $path;
        
        // Token object built using the OAuth library
        $token = new OAuthToken(TOKEN, TOKEN_SECRET);
        
        // Consumer object built using the OAuth library
        $consumer = new OAuthConsumer(CONSUMER_KEY, CONSUMER_SECRET);
        
        // Yelp uses HMAC SHA1 encoding
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $oauthrequest = OAuthRequest::from_consumer_and_token(
            $consumer, 
            $token, 
            'GET', 
            $unsigned_url
        );
        
        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);
        
        // Get the signed URL
        $signed_url = $oauthrequest->to_url();
        
        // Send Yelp API Call
        $ch = curl_init($signed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    } 

    /**
     * Yelp Search API Function by a search term and location 
     * Based on example provided by Yelp here: 
     * https://github.com/Yelp/yelp-api/blob/master/v2/php/sample.php
     */
    function search($location) {
    
        // create new array for GET request
        $url_params = array();
        
        // term is burritos!
        $url_params['term'] = SEARCH_TERM;
        
        // user-provided location
        $url_params['location'] = $location;
        
        // return up to five locations
        $url_params['limit'] = SEARCH_LIMIT;
        
        // sort by closest
        $url_params['sort'] = 1;
        
        // create GET-style search path
        $search_path = SEARCH_PATH . "?" . http_build_query($url_params);
        
        // return JSON data through request() function
        return request(API_HOST, $search_path);
    }
    
    /**
     * Yelp Search API Function by latitude and longitude. 
     * Modified version of search() above.
     */
    function geoSearch($lat, $lng) {
    
        // create new array for GET request
        $url_params = array();
        
        // term is burritos!
        $url_params['term'] = SEARCH_TERM;
        
        // user-provided location
        $url_params['ll'] = $lat . "," . $lng;
        
        // return up to five locations
        $url_params['limit'] = SEARCH_LIMIT;
        
        // sort by closest
        $url_params['sort'] = 1;
        
        // create GET-style search path
        $search_path = SEARCH_PATH . "?" . http_build_query($url_params);
        
        // return JSON data through request() function
        return request(API_HOST, $search_path);
    }
    
    /**
     * Yelp Search API Function by Yelp business ID 
     * Simplified version of search() above.
     */
    function bizSearch($id) {
        
        // create search path using business pathway and business ID
        $biz_path = BIZ_PATH . $id;
        
        // return JSON data through request() function
        return request(API_HOST, $biz_path);
    }

?>
