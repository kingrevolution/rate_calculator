*******************************************************
****            Rate Calculator Widget             ****
*******************************************************

Company: Telinta, Inc.
Website: http://www.telinta.com
Version: 1.0


======================
==-  INTRODUCTION  -==
======================

This widget was created to allow Telinta's customers to display the rates information from a tariff in PortaBilling.

The widget is intended for admin or reseller services. 


======================
==-    FEATURES    -==
======================

    * Reseller or Admin mode
    * Two displaying modes:
      - 'General rate info' mode (destination, country and price per minute for the destination) 
      - 'Rate calculator' mode (destination, country, price per minute and the number of minutes that can be obtained for certain amounts)  
      NOTE: When both modes are selected in the config the customer can choose how to display information. 
    * Configurable currency sign
    * Rates sorting modes (Ascending or Descending)
    * Configurable countries list 
    * Rows number limitation (default - 10)

    
======================
==- CONFIGURATION  -==
======================

    1. Place the files on your web server.
    
    2. Make sure that the web server meets minimal system requirements:
       * Apache 1.x or 2.x or similar web server with PHP module enabled
       * PHP should be compiled with SOAP and SSL extensions           
      
    3. Edit the config.php file. NOTE: To find the i_tariff value please hover cursor over the tariff name in PortaBilling web interface. In the notifications area of your web browser you will see 'https://mybilling.yourdomain.com/tariff.html#edit(20644)'. The value in parentheses is the i_tariff. 
        
    4. If you do not want to show rates for all countries please edit the country_list.php file and remove the unneeded countries.
    
    5. Integrate the rate calculator widget into your web site.

    
=====================
==-   LICENSING   -==
=====================
        
All rights to this software are reserved by Telinta, Inc. 
    
    
=====================
==-      NOTES    -==
=====================
        
* Detailed information about used XML API can be found in the following documentation:  

http://util.telinta.com/link?5850565
http://util.telinta.com/link?3425699
                  
Please contact support@telinta.com if you have any questions. 