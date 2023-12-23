# LBAW Project
This repository is a mirror of the original private repository for this project.

The project was made for the [Laboratório de Bases de Dados e Aplicações Web](https://sigarra.up.pt/feup/en/ucurr_geral.ficha_uc_view?pv_ocorrencia_id=520328) course
in the first semester of the third year of [LEIC](https://sigarra.up.pt/feup/en/CUR_GERAL.CUR_VIEW?pv_curso_id=22841) at [FEUP](https://sigarra.up.pt/feup/en/WEB_PAGE.INICIAL) (Faculty of Engineering of the University of Porto) and 
consisted of making a fully featured project management service, where people can create projects, manage tasks, and have discussions in a small forum, beyond many others.

The [Laravel](https://laravel.com/) PHP framework was used to build this. For more information on the project's development you can check the wiki, more specifically,
[ER](https://github.com/emanuxd11/LBAW-feup/blob/main/Component%20Deliveries/ER.md), [EBD](https://github.com/emanuxd11/LBAW-feup/blob/main/Component%20Deliveries/EBD.md),
[EAP](https://github.com/emanuxd11/LBAW-feup/blob/main/Component%20Deliveries/eap.md) and [PA](https://github.com/emanuxd11/LBAW-feup/blob/main/Component%20Deliveries/pa.md).

### YouTube video showcasing functionalities (link):
[![screenshot of dashboard](https://github.com/emanuxd11/LBAW-feup/blob/main/filesExtra/screenshot-2023-12-23_14%3A57%3A36.png)](https://www.youtube.com/watch?v=_xkL9mtCYWg)

# Dependencies (for running locally)
This project requires a few dependencies:
### On Debian/Ubuntu:
```
sudo apt update
sudo apt install git composer php8.1 php8.1-mbstring php8.1-xml php8.1-pgsql php8.1-curl
```
### On MacOs:
```
brew install php@8.1 composer
```
### On Arch (btw):
```
sudo pacman -Sy
sudo pacman -S php php-pgsql composer docker docker-compose
```
And then enabling `mbstring` `pgsql` `xml` and `curl` extensions in `/etc/php/php.ini`.

Note: If you're on Arch Linux, you will probably be using a higher version of php such as 8.2. In my experience this was fine and there were no issues, but if any problems arise, this could be a factor.

Alternatively, there is also an AUR package for php 8.1, though I have not personally tested it so I cannot vouch for its safety/functionality.

# Running locally
After you're done installing all dependencies, `cd` into the ProjectSync directory and run:
```
composer update
docker compose build
docker compose up -d
php artisan clear
php artisan db:seed
php artisan serve
```
Notes: 
- `clear` shouldn't be needed unless you're actively working on the routes and `db:seed` only needs to be run once.
- Sending emails might not work due to expired SMTP service keys.
If emails don't work and you wish to use this functionality, you can create an account on a service such as [Brevo](https://www.brevo.com/),
which is what we used, and follow [these instructions](https://git.fe.up.pt/lbaw/laravel-integrations/-/tree/develop/01-send-email#smtp-configuration)
to make the appropriate changes in `.env`.

# Credits
### This project was developed by the LEIC students:
- [Emanuel Rui Tavano Maia](https://sigarra.up.pt/feup/pt/fest_geral.cursos_list?pv_num_unico=202107486) (me) - up202107486@up.pt
- [Miguel Nogueira Marinho](https://sigarra.up.pt/feup/pt/fest_geral.cursos_list?pv_num_unico=202108822) - up202108822@up.pt
- [Alberto Daniel Alves Serra](https://sigarra.up.pt/feup/pt/fest_geral.cursos_list?pv_num_unico=202103627) - up202103627@up.pt
- [Rúben Filipe Vaz Fonseca](https://sigarra.up.pt/feup/pt/fest_geral.cursos_list?pv_num_unico=202108830) - up202108830@up.pt
