#!/usr/bin/env python 

import os
import string
import shutil
import time
import pickle
from ftplib import FTP
import sys
import socket

class Gravacao(object):
	def __init__(self,arquivo,tamanho):
		self.arquivo = arquivo
		self.tamanho = tamanho

class Diretorio(object):
	def __init__(self,nome,qtd):
		self.nome = nome
		self.qtd = qtd

def procuraFileLog(diretorio,arquivo,gravacao):
	rst = False
	if verificaDir(endereco+"/log"):
		LOG_FILENAME = endereco+"/log/"+diretorio+".log"
		try:
			arqLog = open(LOG_FILENAME,'rb+')
			while 1:
				try:
					gravacaoLog = pickle.load(arqLog)
					if gravacaoLog.arquivo == gravacao.arquivo and gravacaoLog.tamanho == gravacaoLog.tamanho:
						rst = True
				except(EOFError):
					break
			arqLog.close()
		except(IOError):
			print("Error ao abrir o arquivo "+LOG_FILENAME)
	else:
		print("Erro no dir : "+endereco+"/log")
	return rst

def removeDirLog(diretorio):
	rst = False
	listaDir = []
	if verificaDir(endereco+"/log"):
		LOG_FILENAME = endereco+"/log/enviados.log"
		try:
			arquivo = open(LOG_FILENAME,'rb+')
			while 1:
				try:
					dirLog = pickle.load(arquivo)
					if diretorio.nome != dirLog.nome and diretorio.qtd != dirLog.qtd:
						listaDir.append(dirLog)
				except(EOFError):
					break
			arquivo.close()
		except(IOError):
			print("Error ao abrir o arquivo "+LOG_FILENAME)
		try:
			arquivo = open(LOG_FILENAME,'wb+')
			for i in listaDir:
				pickle.dump(i,arquivo)
			print("Diretorio removido com sucesso: "+diretorio.nome)
			rst = True
		except(IOError):
			print("Error ao abrir o arquivo "+LOG_FILENAME)
	else:
		print("Erro no dir : "+endereco+"/log")
	return rst

def checkEnviado(diretorio):
	rst = False
	if verificaDir(endereco+"/log"):
		LOG_FILENAME = endereco+"/log/enviados.log"
		try:
			arqLog = open(LOG_FILENAME,'rb+')
			while 1:
				try:
					dirArq = pickle.load(arqLog)
					if(isinstance(dirArq,Diretorio)):
						print("OKKKKKKKKKKKKKKKKKKKKKKK")
						if diretorio.nome == dirArq.nome and diretorio.qtd == dirArq.qtd:
							rst = True
							break
						elif diretorio.nome == dirArq.nome and diretorio.qtd > dirArq.qtd:
							removeDirLog(diretorio)
					else:
						print("Errooooooooooooooooo")
					#print(diretorio.nome)
					#print(diretorio.qtd)
					#conexao = conexaoFtp('localhost','ftpcomunix','ftpcomunix')
					#print(len(conexao.nlst("PMSP/receptivo/"+diretorio.nome)))
					#print(dirArq.nome)
					#print(dirArq.qtd)
					#print(len(dirArq.nome))
					#print(len(diretorio.nome))
					#print(diretorio.qtd)
				except(EOFError):
					break
			arqLog.close()
		except(IOError):
			print("Error ao abrir o arquivo "+LOG_FILENAME)
	else:
		print("Erro no dir : "+endereco+"/log")
	return rst

def criaDirLog(diretorio):
	rst = False
	if verificaDir(endereco+"/log"):
		LOG_FILENAME = endereco+"/log/enviados.log"
		try:
			arquivo = open(LOG_FILENAME,'ab+')
			print("DIR :"+diretorio.nome+" :",diretorio.qtd)
			pickle.dump(diretorio,arquivo)
			rst = True
		except(IOError):
			print("Erro ao ler o arquivo\n")
	else:
		print("Erro no dir : "+endereco+"/log")
	return rst

def criaLog(diretorio,arquivo,gravacao):
	rst = False
	if verificaDir(endereco+"/log"):
		LOG_FILENAME = endereco+"/log/"+diretorio+".log"
		try:
			arquivo = open(LOG_FILENAME,'ab+')
			pickle.dump(gravacao,arquivo)
			print("Gravacao inserida no arquivo :" +gravacao.arquivo)
			rst = True
		except(IOError):
			print("Erro ao ler o arquivo\n")
	else:
		print("Erro no dir : "+endereco+"/log")
	return rst

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
	try:
		con = FTP(servidor)
		con.login(usuario,senha)
		return con
	except(socket.error):
		print("Erro ao conectar no FTP!")
		exit(1)

def enviaArqs(dir,arqs,endereco,destinoFtp,servidor,usuario,senha):
	qtd = 0
	for i in arqs:
		#arquivo = dir+"/"+i
		arquivo = dir+"/"+i
		ftpArquivo = i
		if os.path.isfile(arquivo):
			gravacao = Gravacao(arquivo,os.path.getsize(endereco+"/"+arquivo))
			if procuraFileLog(dir,arquivo,gravacao):
				print("Arquivo ja foi enviado "+arquivo)
			else:
				conexao = conexaoFtp(servidor,usuario,senha)
				#if conexao.storbinary("STOR "+destinoFtp+"/"+arquivo, open(arquivo,'rb')):
				if conexao.storbinary("STOR "+destinoFtp+"/"+ftpArquivo, open(arquivo,'rb')):
					##print("Enviado para: "+destinoFtp+"/"+arquivo)
					print("Enviado para: "+destinoFtp+"/"+ftpArquivo)
					##print(conexao.size(destinoFtp+"/"+arquivo))
					print(conexao.size(destinoFtp+"/"+ftpArquivo))
					print(os.path.getsize(endereco+"/"+arquivo))
					#if conexao.size(destinoFtp+"/"+arquivo) == os.path.getsize(endereco+"/"+arquivo):
					if conexao.size(destinoFtp+"/"+ftpArquivo) == os.path.getsize(endereco+"/"+arquivo):
						if criaLog(dir,arquivo,gravacao):
							print("Arquivo inserido no log.")
						#if moveArquivo(endereco,dir,arquivo):
							#print("Arquivo movido com sucesso para backup")
						else:
							print("Erro ao inserir a gravacao no log.")
					else:
						print("Erro ao listar o tamanho dos arquivos")
					conexao.close()
				else:
					print("Arquivo nao encontrado")
		qtd = qtd + 1
	if qtd == len(arqs) and qtd > 0:
		if criaDirLog(Diretorio(dir,qtd)):
			print("Diretorio enviado com sucesso :" +dir,qtd)

def checkDir(dir,dirs):
	rst = False
	for i in dirs:
		d = string.split(i,"/")
		i = d[len(d)-1]
		if dir == i:
			print("Achou "+i)
			rst = True
			break
	return rst

def enviaDir(endereco,destinoFtp,servidor,usuario,senha):
	os.chdir(endereco)
	lista = os.listdir('.')
	#print(lista)
	#print(time.strftime("%Y-%m-%d"))
	dataAtual = time.strftime("%Y-%m-%d")
	if os.path.isdir(dataAtual):
		lista.remove(dataAtual)
	if os.path.isdir("main"):
		lista.remove("main")
	if os.path.isdir("error"):
		lista.remove("error")
	if os.path.isdir("lost+found"):
		lista.remove("lost+found")
	if os.path.isdir("log"):
		lista.remove("log")
	#print(lista)
	lista.sort(reverse=True)
	y = 0
	total = len(lista)
	if len(sys.argv) > 2 :
		if sys.argv[1] == '--dias' or sys.argv[1] == '-d':
			try:
				total = int(sys.argv[2])
			except(ValueError):
				print("Quantidade de dias invalido.!")
	for i in lista:
		conexao = conexaoFtp(servidor,usuario,senha)
		if conexao:
			listaArqs = i
			#if os.path.isdir(i) and i != "backup" and i != dataAtual and i != "log" and y < total:
			if os.path.isdir(i) and y < total:
				listaGrav = os.listdir(listaArqs)
				if checkEnviado(Diretorio(i,len(listaGrav))):
					#continue
				#if len(listaGrav) <= len(conexao.nlst(destinoFtp+"/"+i)):
					print("Os arquivos desse diretorio ja foi enviado! Dir = "+i)
					y = y - 1
				else:
					enviaArqs(i,listaGrav,endereco,destinoFtp,servidor,usuario,senha)
					##if checkDir(i,conexao.nlst(destinoFtp)):
					##	conexao.quit()
					##	enviaArqs(i,listaGrav,endereco,destinoFtp,servidor,usuario,senha)
					##elif conexao.mkd(destinoFtp+"/"+i):
					##		conexao.quit()
					##		enviaArqs(i,os.listdir(listaArqs),endereco,destinoFtp,servidor,usuario,senha)
					##else:
					##	print("Erro ao criar o dir no servidor FTP!")
				y = y + 1
		else:
			print("Erro")
		#print("DIR ::::")
		#print(y)
		#print(total)
	print("END : "+endereco)



# Informacoes Servidor FTP
servidor = '10.11.195.30'
usuario = 'ftpcomunix'
senha = 'FtPC0muniX'
endereco = '/home/extend/calls'
destinoFtp = 'sebrae/bilhetes/SIP01'

#Enviando os bilhetes
enviaDir(endereco,destinoFtp,servidor,usuario,senha)
