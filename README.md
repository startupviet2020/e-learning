**MySQL Connection**

***Download Cloud MySQL Proxy***
`curl -o cloud_sql_proxy https://dl.google.com/cloudsql/cloud_sql_proxy.darwin.amd64`
`chmod +x cloud_sql_proxy`
***Running cloud_sql_proxy***
`./cloud_sql_proxy -instances=e-invoice-218907:asia-southeast1:einvoice=tcp:3306 -credential_file=backend/e-invoice-218907-41d4143058a4.json`
***Connect from mysql client***
`server: 127.0.0.1`
`user: root`
`password: qazwsxedc`

**Install depenencies**
`php composer.phar install`

**Run development web server**
`cd api/public`n
`php -S 0.0.0.0:8080 www-route.php`