#!/usr/bin/env python

import os
import string
import shutil
from ftplib import FTP

servidor = '192.168.10.235'
usuario = 'ftpcomunix'
senha = 'FtPC0muniX'
endereco = '/home/gravacoes/receptivo'
destinoFtp = 'mds/receptivo'
enderecoAtivo = '/home/gravacoes/ativo'
destinoFtpAtivo = 'mds/ativo'

def verificaDir(endereco):
	rst = False
	if os.path.exists(endereco):
		rst = True
	else: 
		os.mkdir(endereco)
		if os.path.exists(endereco):
			print("Diretorio criado com sucesso : "+endereco)
			rst = True
	return rst

def moveArquivo(endereco,dir,arquivo):
	rst = False
	if verificaDir(endereco+"/"+"backup"):
		if verificaDir(endereco+"/"+"backup"+"/"+dir):
			shutil.copy(endereco+"/"+arquivo,endereco+"/"+"backup"+"/"+dir)
			if os.path.getsize(endereco+"/"+arquivo) == os.path.getsize(endereco+"/"+"backup"+"/"+arquivo):
				os.remove(endereco+"/"+arquivo)
				rst = True
		else:
			print("Erro ao criar o dir :"+endereco+"/"+"backup"+"/"+dir)
	else:
		print("Erro ao criar o dir :"+endereco+"/"+"backup")
	return rst


def conexaoFtp(servidor,usuario,senha):
	con = FTP(servidor)
	con.login(usuario,senha)
	return con

def enviaArqs(dir,arqs,endereco,destinoFtp,servidor,usuario,senha):
	for i in arqs:
		arquivo = dir+"/"+i
		if os.path.isfile(arquivo):
			conexao = conexaoFtp(servidor,usuario,senha)
			if conexao.storbinary("STOR "+destinoFtp+"/"+arquivo, open(arquivo,'rb')):
				print("Enviado para: "+destinoFtp+"/"+arquivo)
				print(conexao.size(destinoFtp+"/"+arquivo))
				print(os.path.getsize(endereco+"/"+arquivo))
				if conexao.size(destinoFtp+"/"+arquivo) == os.path.getsize(endereco+"/"+arquivo):
					if moveArquivo(endereco,dir,arquivo):
						print("Arquivo movido com sucesso para backup")
				else:
					print("Erro ao listar o tamanho dos arquivos")
			conexao.close()
		else:
			print("Arquivo nao encontrado")

def checkDir(dir,dirs):
	rst = False
	for i in dirs:
		d = string.split(i,"/")
		i = d[len(d)-1]
		print("DIRS "+ i +" "+dir)
		if dir == i:
			print("Achou "+i)
			rst = True
			break
	return rst

def enviaDir(endereco,destinoFtp,servidor,usuario,senha):
	os.chdir(endereco)
	lista = os.listdir('.')
	lista.sort(reverse=True)
	for i in lista:
		conexao = conexaoFtp(servidor,usuario,senha)
		if conexao:
			listaArqs = i
			if os.path.isdir(i) and i != "backup":
				if checkDir(i,conexao.nlst(destinoFtp)):
					conexao.quit()
					enviaArqs(i,os.listdir(listaArqs),endereco,destinoFtp,servidor,usuario,senha)
				elif conexao.mkd(destinoFtp+"/"+i):
						conexao.quit()
						enviaArqs(i,os.listdir(listaArqs),endereco,destinoFtp,servidor,usuario,senha)
		else:
			print("Erro")

#Enviando as gravacoes Receptivas
#enviaDir(endereco,destinoFtp,servidor,usuario,senha)
#Enviando as gravacoes Ativas
enviaDir(enderecoAtivo,destinoFtpAtivo,servidor,usuario,senha)
