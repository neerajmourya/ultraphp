<?php
return [
    /**
     * Sets the name of the session
     */
    'SESSION_NAME' => 'ULTRAPHP_SESSION',
    
    /**
     * Validity of the session
     * from last activity
     */
    'SESSION_VALIDITY' => 3600,
    
    /**
     * Set whether session should use only cookies and not url
     * Keep it true as it is secured
     * Set to false to allow url based session
     */    
    'SESSION_USE_ONLY_COOKIES' => true,
    
    /**
     * If TRUE cookie will only be sent over secure connections.
     * Keep it false if not using https
     */
    'SESSION_SECURE' => false,
    
    /**
     * If set to TRUE 
     * then PHP will attempt to send the httponly flag
     * when setting the session cookie
     */
    'SESSION_HTTPONLY' => true,
];