Bz-Cache
========
类似CDN，用于动静分离。
如 http://a.com/style.css ,可以分离另一台主机 http://cache.b.com/xxx/style.css，cache.b.com 会自动缓存 a.com文件。

1、推荐用nginx
2、apache 的.htaccess 会影响性能，最好写到.conf里面
