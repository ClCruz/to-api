# API 
### Builded in PHP.  

#### How to install  

#### Install for:  

```bash
sudo docker run -it -d -p 2002:80 --name tixsme.api \
-v /var/www/tixsme/api:/var/www/html \
-v /var/www/tixsme/media:/var/www/media \
-v /var/www/tixsme/site:/var/www/site \
blcoccaro/linuxphpsql:v4
```

### meanings 
| location | what |
| -------- | ----- |
| /var/www/tixsme/api | where is the source of api |
| /var/www/tixsme/media | where is the images |
| /var/www/tixsme/site | where is the build of site (dist) |

### .htaccess
```.htaccess
<IfModule mod_rewrite.c> 
RedirectMatch 404 \.json 
RewriteEngine On 
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteRule ^([^\.]+)$ $1.php [NC,L] 
</IfModule> 
```

![MC HAMMER](https://camo.githubusercontent.com/294d473d32d1d33750ea6a059bcd44cf31398535/687474703a2f2f692e696d6775722e636f6d2f6163484d3330786c2e6a7067)