#!/bin/bash 

qtdReceptivo=20
qtdAtivo=10
dirGrav=/home/gravacoes
tamMax=85

rmGrav(){
	if [ -d $dir ] ; then
		cd $dir
		rst=`echo $?`
		dataAtual=`date +%Y`
		if [ $rst -eq 0 ] ; then
			qtd=`ls |grep ^[1-2]|awk -F "-" -v data=$dataAtual '{if($1 <= data ){ print $0}}'|wc -l`
			if [ $qtd -gt $max ] ; then
				echo "Remove `ls |grep ^[1-2]|awk -F "-" -v data=$dataAtual '{if($1 <= data ){ print $0}}'|sort|head -n1`"
				sleep 6
				rm -r `ls |grep ^[1-2]|awk -F "-" -v data=$dataAtual '{if($1 <= data ){ print $0}}'|sort|head -n1`
				qtdCheck=0
			else
				echo "Max $qtd"
				qtdCheck=`expr $qtdCheck + 1`
			fi
		fi
	fi
}
check=true
qtdCheck=0
while $check ; do
	tam=`df $dirGrav|awk -F " " '{print $5}'|grep -vi use|cut -d"%" -f1`
	if [ `echo ${#tam}` -gt 0 ] ; then
		if [ $tam -gt $tamMax ] && [ $qtdCheck -lt 2 ] ; then
			echo "Maior $tam $tamMax"
			dir="$dirGrav/receptivo/backup"
			max=`echo $qtdReceptivo`
			rmGrav
			dir="$dirGrav/ativo/backup"
			max=`echo $qtdAtivo`
			rmGrav
		else
			echo "Menor $tam $tamMax"
			check=false
		fi
	else
		echo "Erro na porcetangem do disco"
	fi
done
