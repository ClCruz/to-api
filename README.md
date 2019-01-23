# API 
### Builded in PHP.  

#### How to install  

#### Install for:  

```bash
sudo docker run -it -d -p 2002:80 --restart=always --name unique.api \
-v /hd/www/unique/api/:/var/www/html \
-v /hd/www/unique/media/:/var/www/media \
-v /hd/www/unique/site/:/var/www/site \
-v /hd/www/unique/legacy:/var/www/legacy \
-v /hd/www/unique/gitauto/:/var/www/gitauto \
blcoccaro/phpwithsql:v1
```

### meanings 
| location | what |
| -------- | ----- |
| /var/www/unique/api | where is the source of api |
| /var/www/unique/media | where is the images |
| /var/www/unique/site | where is the build of site (dist) |

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