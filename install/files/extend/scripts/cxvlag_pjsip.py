#!/usr/bin/python
#!conding: utf-8
#CRONTAB = * * * * * /home/extend/scripts/cxvlag_pjsip.py

import sys
import os
import time
from datetime import datetime

#------------------
qtd_latencia = 100
log_arquivo = "/home/cxvlag_pjsip.txt"
tempo_monitoramento = 1 #em segundos
#------------------

while True:
    data = datetime.today().strftime("%d-%m-%Y--%H-%M-%S")
    pid = os.popen("ps uxa | grep cxvlag_pjsip.py | grep -v grep | tr -s ' ' | cut -d' ' -f2 ").read()
    qtd_pid = int(len(pid.replace("\n","-").split("-")))

    if qtd_pid > 3:
        print("Aplicacao ja em execucao")
        exit()


    lista_contacts = os.popen("/usr/sbin/asterisk -rx 'pjsip show contacts' | tr -s ' ' | grep 'Contact:' | grep -v 'Contact: <Aor/ContactUri'")

    for linha in lista_contacts:
        contact = linha.split(" ")[2]
        latencia = float(linha.split(" ")[5])
        if latencia > qtd_latencia:
            log = "[" + str(data) + "] " + "Contact: " + str(contact) + " -- Latencia: " + str(latencia)
            os.system("echo '" + log + "' >> " + log_arquivo)

    tamanho_log = os.popen(" du -sh /home/cxvlag_pjsip.txt ").read()[0:5].strip()

    if tamanho_log == "10G":
        os.system("echo > " + log_arquivo)

    time.sleep(tempo_monitoramento)

