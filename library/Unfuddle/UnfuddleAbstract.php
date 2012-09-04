<?php

namespace Unfuddle;

use Exception;

/**
 * Abstract class for Unfuddle API objects
 */
abstract class UnfuddleAbstract
{
    const URL_SEPARATOR = '/';

    /**
     * @var string
     */
    protected $apiString = 'api/v1/';

    /**
     * @var Connection
     */
    protected $connection;
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection 	= $connection;
        $this->baseUrl		= $this->getProtocol() . $this->trailingSlashFilter($this->connection->getDomain()) . $this->apiString;
    }

    /**
     * @return string
     */
    protected function getProtocol()
    {
        if ($this->connection->getSsl())
        {
            return 'https://';
        }

        return 'http://';
    }

    /**
     * @param $url
     * @return string
     */
    protected function trailingSlashFilter($url)
    {
        if (substr($url, -1) === self::URL_SEPARATOR)
        {
            return $url;
        }

        return trim($url) . self::URL_SEPARATOR;
    }

    /**
     * @param $requestBodyLength
     * @return array
     */
    protected function getHeaders($requestBodyLength)
    {
        $headers[] = "MIME-Version: 1.0";
        $headers[] = 'Accept: application/xml';
        $headers[] = 'Content-type: application/xml; charset=utf-8';
        $headers[] = "Content-length: " . $requestBodyLength;
        $headers[] = "Cache-Control: no-cache";

        return $headers;
    }

    /**
     * @param $response
     * @throws \Exception
     */
    protected function testHttpStatusCode($response)
    {
         // Get HTTP Status code from the response
        $statusCode = array();
        preg_match('/\d\d\d/', $response, $statusCode);

        switch($statusCode[0])
        {
            case 200:
                // general OK
                break;
            case 201:
                // resource created
                break;
            case 202:
                // accepted
                break;
            case 401:
                throw new Exception('Unauthorized (401): The credentials specified via HTTP Basic Authentication were invalid.');
                break;
            case 400:
                throw new Exception('Bad Request (400): The request body was malformed or the record you are updating has an incorrect field value. A response with this status is often accompanied by a list of errors in the response body.');
                break;
            case 404:
                throw new Exception('Not Found (404): The resource you have referenced could not be found.');
                break;
            case 405:
                throw new Exception('Method Not Allowed (405): The method you are using to access (GET, POST, PUT, etc.), is not allowed for the requested resource.');
                break;
            case 406:
               throw new Exception('Not Acceptable (406): You have referenced the media type requesting a format that is unsupported.');
               break;
            case 415:
               throw new Exception('Unsupported Media Type (415): You have referenced the media type requesting a format that is unsupported. Most resources are available as XML only, though a few have alternate types as described in this API documentation.');
               break;
            case 500:
               throw new Exception('Internal Server Error (500): There has been an error from which Unfuddle could not recover. There is a high likelihood that an internal server error represents a bug on the part of Unfuddle. Unfuddle Support automatically receives notifications of these errors for further investigation.');
               break;
            default:
                throw new Exception('Call to Unfuddle returned an unexpected HTTP status of:' . $statusCode[0]);
        }
    }
    
 	// from http://php.oregonstate.edu/manual/en/function.http-parse-headers.php#77241
    /**
     * @param $header
     * @return array
     */
    protected function httpParseHeaders($header)
    {
        $retVal = array();
        $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
        
        foreach( $fields as $field ) 
        {
            if( preg_match('/([^:]+): (.+)/m', $field, $match) ) 
            {
                $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
                if( isset($retVal[$match[1]]) ) 
                {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                }
                else 
                {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
        return $retVal;
    }
    
    // from http://us2.php.net/manual/en/function.htmlentities.php#78371
    /**
     * @param $str
     * @return mixed
     */
    protected function XMLStringFormat($str)
    {
        $str = str_replace("&", "&amp;", $str); 
        $str = str_replace("<", "&lt;", $str);  
        $str = str_replace(">", "&gt;", $str);  
        $str = str_replace("'", "&apos;", $str);   
        $str = str_replace("\"", "&quot;", $str);  
        $str = str_replace("\r", "", $str);

        return $str;
    }

    /**
     * @param $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}