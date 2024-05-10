#!/usr/bin/python
#coding: utf-8
#-----------------------------------------
# Name: cxv_monitor
# Created by: Jorge Silva
# Made in: 02/07/2020
#-----------------------------------------
#crontab: * * * * * /usr/bin/python /home/whatsapp/cxv_trata_monitor.py


import os
import commands

py_cxv_data = ''

#--- Metodo cria diretorio de log
def cria_dir(arquivo):
        if not os.path.exists(arquivo):
		os.mkdir(arquivo)

#Lista que sera comparada com os trata que estao atualmente rodando
py_cxv_trata_lista='/home/extend/trata_lista_monitor'

#Lista com trata atualmente rodando
py_cxv_trata_on = commands.getoutput('ps aux | grep omnixserver')

#Local onde sera guardados os logs de queda
py_cxv_log_local_dir='/home/extend/log_trata'

#Nome do aquivo de log
py_cxv_log_local_name=py_cxv_log_local_dir + '/trata_log'

#cria diretorio de log
cria_dir(py_cxv_log_local_dir)

#compara os as duas listas e sobe o trata
with open(py_cxv_trata_lista, 'r') as data:
	next(data)
	for line in data:
		item = line.strip()
		if item in py_cxv_trata_on:
			None
		else: 
			os.system('/etc/init.d/./' + item)
			py_cxv_data=commands.getoutput('date +%d/%m/%Y-%H:%M:%S')
			os.system('echo ' + py_cxv_data + ' ' + item + ' >> ' + py_cxv_log_local_name)
