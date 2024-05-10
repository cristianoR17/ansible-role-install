#!/bin/bash

#
# v0.4
#
# Script para fazer backups dos inserts que sao gerados na central, 
# Modificado - Gilcimar Soares Leite
# Script não faz mais a compactação dos diretórios apenas empacotando os arquivos.
# Adicionado a função -d que se refere ao número de dias exceto ao dia atual: exemplo ./backup_insert_v2.sh -d 2
# Adicionado a função -a para ser feito backup de todos os dias exceto ao dia atual: exemplo ./backup_insert_v2.sh -a
# Data 14-05-2014
# Modificado - Gilcimar Soares Leite
# Adicionado o recurso para checar o arquivo compactado, existindo e gerado o arquivo com outro nome no 
# formato "DATA.QUANTIDADE_BKP.tar"
# Data 24-07-2014
#
# Modificado - Gilcimar Soares Leite
# Data 20-10-2014
# Adicionado a funcionalidade de compactacao dos bilhetes, com uma melhor otimizacao 1000%
# no tempo de compactacao.
#
# 
# 
# 
# Obs.:
# - Licenca: "GNU Public License v2"
# - Dependencias: expr, grep e sed
# - Plugin testado somente em sistemas Linux
# - Plugin passivel de melhorias ;)

dir=/home/extend/calls/backup
dirDest=/home/extend/calls/backup

checkFile(){
	arq="$i"
	cont=0
	check=true
	while $check ; do
		if [ -f $dir/$arq.tar.gz ] ; then
			echo "Existe $dir/$arq.tar.gz"
			arq="$i.$cont"
			cont=`expr $cont + 1`
		else
			echo "Saindo......"
			sleep 2
			i="$arq"
			check=false
		fi
		sleep 5
	done
}

backupInsert(){
dataAtual=`/bin/date +%F`

for i in $dias ; do
	if [ $i != $dataAtual ] ; then
		rst=30
		y=$i
		checkFile
		echo "Zipandoo.........$i.tar.gz"
		tar cvpf /tmp/$i.tar $y
		rst=`echo $?`
		if [ $rst -eq 0 ] ; then
			echo "Copiando o arquivo empacotado e compactando."
			mv /tmp/$i.tar $dirDest/ && tar zcf $i.tar.gz $i.tar && rm -rv $y $i.tar
		fi
	fi
done

}

# Checando as opcoes definidas pelo usuario:
if [ $# -gt 0 ] ; then
	if [ -d /home/extend/calls/backup ] ; then
		cd /home/extend/calls/backup
	else
		echo "falha ao acessar o $dir"
		exit 1
	fi
	case $1 in
		# Obtendo ajuda:
		-a | --all)
		shift
		echo "Realizando o backup de todos os dias."
		sleep 2
		dias=`ls |grep -E "^1|^2"|grep -v .tar`
		backupInsert
		exit 1
		;;
		# Obtendo o nome do cliente a ser criado:
		-d | --dias)
		shift
		dias=`ls |grep -E "^1|^2"|grep -v .tar|head -n $1`
		echo "Realizando o backup dos dias $dias"
		sleep 2
		backupInsert
		exit 1
		;;
		# Caso alguma opcao invalida seja especificada:
		-*)
		echo "[$1]: Opcao invalida\!"
		echo "Faca: '$prog --ajuda ou $prog -a' para obter ajuda."
		exit 1
		;;
		*)
		echo "[$1]: Opcao invalida\!"
		echo "Faca: '$prog --ajuda ou $prog -a' para obter ajuda."
		exit 1
		;;
	esac
else 
	echo "Falta de parametros!"
	echo "Faca: '$prog --ajuda ou $prog -a' para obter ajuda."
fi

