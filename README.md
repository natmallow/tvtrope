## Welcome to Nate's take home!

Hello, 
This is a here thetvdb and the "house of lines" take home

Setup instructions.  
Download [xamp](https://www.apachefriends.org/index.html)  and install.  
Open your `hosts` file as Admin; on win7 it is located:  

    $:\Windows\System32\drivers\etc

Add `127.0.0.1    dev.tvtrope.com`  
Save it.  

Now go to the  `$\xampp\htdocs\` and create the directory `tvtrope`  
Go to `$\xampp\apache\conf\extra` and open the `httpd-vhosts.conf`  
Paste this in:  

	    <VirtualHost *:80>
	       DocumentRoot "$:/xampp/htdocs/tvtrope"
	       ServerName dev.tvtrope.com
	    </VirtualHost>

**Note**  
 Be sure to replace the **$** with the drive name  

Clone or Download the https://github.com/natmallow/tvtrope to your `$\xampp\htdocs\tvtrope`directory  

Now open up xamp and start the Apache service!  

 
