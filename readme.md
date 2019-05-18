

# myLaravelTemplate

laravelの環境をDockerで作っています。
webサーバーは、いろいろpython/golang etc使う可能性を考えてcentos7をベースにapache2.4とphp7.2とmysql5.7

## 環境設定

docker / docker-composeが入っている前提  

1.最新のリポジトリをclone

```

$ git clone git@github.com:yamaaaaaa/myLaravelTemplate

```

2.laravelプロジェクトを作成(srcフォルダを基準にしているのでsrc)

```
$ composer create-project --prefer-dist laravel/laravel src "5.5.*"
```

3.".env"を編集

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=pass
```

4.docker-composeでアップ

```
$ docker-compose up -d
```

4.次の様の起動していたらOK

```
$ docker ps
CONTAINER ID        IMAGE                    COMMAND                  CREATED             STATUS              PORTS                                            NAMES
884329fd567f        mylaraveltemplate_web    "/usr/sbin/init"         53 seconds ago      Up 52 seconds       0.0.0.0:80->80/tcp                               mylaraveltemplate_web_1
f91ab20eea67        mysql:5.7                "docker-entrypoint.s…"   54 seconds ago      Up 52 seconds       0.0.0.0:3306->3306/tcp, 33060/tcp                mylaraveltemplate_mysql_1
f1297904506e        schickling/mailcatcher   "mailcatcher --no-qu…"   54 seconds ago      Up 52 seconds       0.0.0.0:1025->1025/tcp, 0.0.0.0:1080->1080/tcp   mylaraveltemplate_mailcatcher_1
```

ブラウザでも確認
http://localhost

