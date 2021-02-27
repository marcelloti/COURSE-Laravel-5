 
FROM debian:buster-slim

MAINTAINER Marcello Costa

### Variáveis ###

# Nome do arquivo vhost
ARG vhostfile=app

# Caminho dos diretórios
ARG pathHtml=/var/www/html


# Caminho do arquivo php.ini
ARG phpIni=/etc/php/7.3/apache2/php.ini

# Porta padrão do apache
#ARG apachePort=80

#################


CMD ["/bin/bash"]

#########################
##      MÁQUINA        ##
#########################

# Configurando ssh
RUN sed -i 's/deb.debian.org/ftp.br.debian.org/g' /etc/apt/sources.list
RUN apt update && apt install ssh openssh-server -y
RUN echo "root:root" | chpasswd
RUN sed -i '/^PermitRootLogin/s/without-password/yes/' /etc/ssh/sshd_config

# Corrigindo timezone
RUN cat '/usr/share/zoneinfo/America/Sao_Paulo' > /etc/localtime

# Locales
RUN apt-get install -y locales && rm -rf /var/lib/apt/lists/* && localedef -i pt_BR -c -f UTF-8 -A /usr/share/locale/locale.alias pt_BR.UTF-8

# apt-utils
RUN apt update && apt-get install -y --no-install-recommends apt-utils

# Pacotes adicionais
RUN apt-get install -y --allow-unauthenticated wget zip build-essential vim curl git sudo iputils-ping 


#########################
##    PHP  + APACHE    ##
#########################

####
#    APACHE
####

# Instalando apache
RUN apt-get install apache2 -y
RUN echo "127.0.0.1 app.local" >> /etc/hosts
RUN echo "# Correção do erro: Could not reliably determine the server's fully qualified domain name" >> /etc/apache2/apache2.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Aliases
# ADD ${vhostfile}-aliases.conf /etc/apache2/conf-available/

# Vhost
# ADD ${vhostfile}.conf /etc/apache2/sites-available/
# RUN a2ensite ${vhostfile}; exit 0

# Apagando site default do apache
RUN a2dissite 000-default && rm /etc/apache2/sites-available/000-default.conf

# Apagando o diretório padrão do apache
# RUN rm -r /var/www

# Ativando módulos apache
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod proxy
RUN a2enmod proxy_http
RUN a2enmod proxy_ajp
RUN a2enmod rewrite
RUN a2enmod deflate
RUN a2enmod headers
RUN a2enmod proxy_balancer
RUN a2enmod proxy_connect
RUN a2enmod proxy_html

# Ativando xml2enc
RUN dpkg -S /usr/lib/apache2/modules/mod_xml2enc.so && a2enmod xml2enc

# Alterando o ports do apache
# RUN cat /etc/apache2/ports.conf | sed "s/Listen 80/Listen ${apachePort}/" | sed "s/Listen 443/Listen ${apachePort}/" > /etc/apache2/ports-tmp.conf
# RUN rm /etc/apache2/ports.conf
# RUN mv /etc/apache2/ports-tmp.conf /etc/apache2/ports.conf

# Ativando MPM
RUN a2dismod mpm_event && a2enmod mpm_prefork && service apache2 restart

####
#    PHP
####

# Baixando pacotes (PHP, PHP-postgresql, xdebug etc)
RUN apt-get install -y --allow-unauthenticated php php-common php-cli libapache2-mod-php php-curl php-gd php-intl php-json php-mysql php-pgsql php-readline php-dev php-xdebug php-mbstring

# Configurando PHP
RUN cat ${phpIni} | sed 's/;date.timezone =/date.timezone = America\/Sao_Paulo/' | sed 's/post_max_size = 8M/post_max_size = 50M/' | sed 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' | sed 's/max_file_uploads = 2M/max_file_uploads = 20/' > ${phpIni}.tmp
RUN mv ${phpIni} ${phpIni}.original
RUN mv ${phpIni}.tmp ${phpIni}

# Habilitando Xdebug
# RUN echo "xdebug.remote_enable=1" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_handler=dbgp" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_host=localhost" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_port=9000" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_log="/tmp/xdebug.log"" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.idekey=PHPSTORM" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_autostart=on" >> /etc/php7/apache2/conf.d/20-xdebug.ini
# RUN echo "xdebug.remote_connect_back=0" >> /etc/php7/apache2/conf.d/20-xdebug.ini

# Composer & Laravel
# RUN apt install composer -y
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN composer global require "laravel/installer"

# Adicionando script inicial no container
ADD ./containerInit.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh
ENTRYPOINT /usr/local/bin/init.sh && /bin/bash
