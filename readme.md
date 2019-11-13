

# myLaravelTemplate

laravelの環境をDockerで作っています。
webサーバーは、いろいろ応用自由を考えてcentos7をベースにapache2.4とphp7.2とmysql5.7

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

5.PHPStorm設定

- phpstormではappフォルダをリソースルートに

6.laravelmix設定

- laravel-mixにmix.setPublicPath('public');追記
- vendorは別途コンパイルにしたほうが軽い?はず(未検証



## Laravel初期設定


### config/app.php設定

```
    'timezone' => 'Asia/Tokyo',
    'locale' => 'ja',
    'fallback_locale' => 'ja',
```

### CustomValidator

```
$ php artisan make:rule Uppercase
```

参考  
[https://search.readouble.com/?query=6.x+%E3%82%AB%E3%82%B9%E3%82%BF%E3%83%A0%E3%83%90%E3%83%AA%E3%83%87%E3%83%BC%E3%82%B7%E3%83%A7%E3%83%B3%E3%83%AB%E3%83%BC%E3%83%AB](https://search.readouble.com/?query=6.x+%E3%82%AB%E3%82%B9%E3%82%BF%E3%83%A0%E3%83%90%E3%83%AA%E3%83%87%E3%83%BC%E3%82%B7%E3%83%A7%E3%83%B3%E3%83%AB%E3%83%BC%E3%83%AB)


### HTML/Form、Image、

```
$ composer require "laravelcollective/html"
$ composer require intervention/image

// config/app.phpへ登録
	//providorsへ
		Intervention\Image\ImageServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
    //aliasへ
        'Image' => Intervention\Image\Facades\Image::class,  
        'Form' => Collective\Html\FormFacade::class,    

```

### ヘルパー関数の準備

```
//1.app/Helpers/helper.phpを作成
//2.app/Providers/AppServiceProvider.phpに読込追記
    public function register()
    {
	    require_once app_path().'/Helpers/helper.php';
    }

```


### View作成用のartisan拡張

```
$ composer require sven/artisan-view --dev

//AppServiceProvidoeなどに追記
	public function register()
	{
	    if ($this->app->environment() !== 'production') {
		    $this->app->register(\Sven\ArtisanView\ServiceProvider::class);
	    }
	}

```

### pipe-dream

```
$ composer require --dev pipe-dream/laravel
//作成して http://localhost/pipe-dream　へアクセス
```

- pipe-dreamスキーマサンプル

```
Company
name

Shop
company_id
name

Role
name

User
id
role_id
company_id
name
email
password
remember_token
email_verified_at

user_shop
note

Comment
user_id

```

- Auth関連

```
$ php artisan migrate
$ composer require laravel/ui
$ php artisan ui vue --auth
$ npm install
$ npm run dev

//config/app.phpを修正
'locale' => 'ja',

```

- resources/views/auth/以下その他のviewを日本語へ

- resources/lang/en をコピーして jaにして日本語に

### Authメールの日本語化

- カスタムしたNotificationを作成

```
$ php artisan make:notification RestPasswordNotificationJp
```

```
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotificationJp extends Notification
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        return (new MailMessage)
            ->subject('パスワード再設定のお知らせ')
            ->line('下のボタンをクリックしてパスワードを再設定してください。')
            ->action('パスワード再設定', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('もし心当たりがない場合は、本メッセージは破棄してください。');
    }
    
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
```

- User.phpで sendPasswordResetNotification()をOverride

```
	public function sendPasswordResetNotification($token){
        $this->notify(new ResetPasswordNotificationJp($token));
    }
```

- HTMLメールのテンプレートを作成

```
$ php artisan vendor:publish --tag=laravel-notifications
```

- (resources/views/vendor/notifications/email.blade.phpを日本語にする)

```
@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# エラーが発生しました。
@else
# パスワード再設定のお知らせ
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
{{ $actionText }}ボタンをクリックできない場合は、以下のURLへ直接アクセスしてください。
[{{ $actionUrl }}]({!! $actionUrl !!})
@endslot
@endisset
@endcomponent
```











































