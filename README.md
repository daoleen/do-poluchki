[Установка PHP]  
Будем использовать mise - это фиговина, позволяющая управлять версиями PHP. В данном случае через нее мы скажем что 
чисто для папки этого проекта нужно использовать PHP версии 8.5. Mise сохраняет настройки в файле mise.toml  

Установите зависимости для сборки PHP и сам mise из официальных репозиториев Arch:
  $ sudo pacman -S --needed mise base-devel autoconf bison re2c libxml2 sqlite curl libpng libjpeg-turbo freetype2 libxslt libzip icu libsodium postgresql-libs librabbitmq-c

Подключите mise к вашей оболочке (судя по вашему Dockerfile, вы используете fish):
  $ echo 'mise activate fish | source' >> ~/.config/fish/config.fish
Перезапустить fish.  

Установите PHP 8.5 строго для папки вашего проекта:
Перейдите в корневую папку будущего проекта на Symfony и выполните:
  $ cd ~/projects/my-symfony-app
  $ mise use php@8.5

Установите расширения xdebug и amqp для этого PHP:
Так как вы внутри папки проекта, команда pecl уже относится к локальному PHP 8.5:
  $ pecl install xdebug
  $ pecl install amqp
(При запросе пути к librabbitmq во время установки amqp просто нажмите Enter — он найдет системную библиотеку сам).  

Добавьте ваши настройки php.ini (включая Xdebug):
Узнайте, где лежит папка конфигураций для этого изолированного PHP, и создайте там ваш файл:
  # Посмотреть путь к папке с конфигами
  $ php --ini

В выведенной директории Scan for additional .ini files in: создайте файл 99-custom.ini со следующим содержимым (на основе вашего файла, но адаптированным под локалку):
<pre>
extension=amqp.so
zend_extension=xdebug.so

memory_limit = -1

[xdebug]
xdebug.mode=debug,coverage
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
xdebug.start_with_request=yes
xdebug.idekey=PHPSTORM
xdebug.log_level=0
</pre>

Настройка PhpStorm:
В настройках PhpStorm (Settings -> PHP -> CLI Interpreter) просто укажите путь к бинарнику, который выдаст команда 
which php внутри папки вашего проекта (обычно это ~/.local/share/mise/installs/php/8.5.x/bin/php). 
IDE увидит и версию, и Xdebug без каких-либо пробросов портов.

[Установка Symfony CLI]
По аналогии с PHP установим конкретную версию Symfony для этой папки через mise:
  $ mise use github:symfony-cli/symfony-cli@5.22.2
Проверяем командами:
  $ symfony -v
  $ which symfony

[Установка Composer]
Аналогичным образом через mise ставим композёр:
  $ mise use github:composer/composer@2.11.2
