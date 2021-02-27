#!/bin/bash

# Lógica que só será executa uma única vez
testFile="/tmp/CONTAINER_ALREADY_STARTED"
if [ ! -e $testFile ]; then
    touch $testFile
    a2ensite app.conf
fi

# Aplicando mudanças no apache e iniciando o serviço
/etc/init.d/apache2 restart &

# Ativando ssh
/etc/init.d/ssh restart &

exit 0;
