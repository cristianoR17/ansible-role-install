#!/bin/bash
#
# v0.8.3
#
# Script para cricao Backups
# Modificado - Gilcimar Soares Leite
# Data 13-09-2010
# Modificado - Marco Antonio Aquilla
# Data 23-05-2011(Acrecimo da funcao "enviaSMB")
# Modificado - Gilcimar Soares Leite
# Data 23-04-2013
# Modificado - Samuel O. G. Santos
# Data 08-08-2013 
# Modificado - Gilcimar Soares Leite
# Data 08-10-2013 (Melhoria no script e adicionado a funcao quantidade de arquivos backup)
# Modificado - Gilcimar Soares Leite
# Data 28-10-2013 (Melhoria no script e adicionado a funcao extendphoneBkp)
# Modificado - Gilcimar Soares Leite
# Data 17-09-2015 ( Adicionado a funcao backupLogExtend para realizar o backup da /home/extend/log
# Modificado - Gilcimar Soares Leite
# Data 19-02-2016 Realizacao do backup /home/extend/calls/backup ao qual necessite configurar a variavel
# backupCallsBackup=true, caso contrario nao sera feito o backup e o backup e incremental adicionando apenas os arquivo que foram alterados. 
# Modificado - Edgard Carrilho
# Data 19-07-2016 Atualizado o caminho do diretorio do SITE para nova versao manager
# Obs.:
# - Licenca: "GNU Public License v2"
# - Dependencias: expr, grep e sed
# - Plugin testado somente em sistemas Linux
# - Plugin passivel de melhorias ;)

nomeServidor=$(uname -n)
data=$(date +%Y-%m-%d -d '-1 day')
diretorioTemp="/usr/src/"
bkpDiretorio="$diretorioTemp"bkp_"$nomeServidor"
tipo=" "


if [ ! -d /home/bkpComunix ] ; then
        mkdir -p /home/bkpComunix
        destinoBkp="/home/bkpComunix"

        else
                destinoBkp="/home/bkpComunix"
fi

checkQtdBackup(){

    totalBkp=`ls $destinoBkp|grep $(uname -n)_$tipo -c`
    rst=`expr $totalBkp - $qtdBkp`
    if [ $rst -gt 0 ] ; then
        arquivos=`ls $destinoBkp|grep $(uname -n)_$tipo |head -n$rst`
       for i in $arquivos ; do
          echo "Removendo arquivo $i"
          rm $destinoBkp/$i
       done
    fi

}

backupSvn(){

        tipo=SVN
        geraDiretorio
        cd /home
        if [ -d svn ] ; then

            tar czpf $bkpDiretorio/svn.tar.gz svn
            cd $bkpDiretorio
            cd ..
            arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
            tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
            mv $arquivoBkp $destinoBkp

        fi

}

backupPentaho(){

        tipo=PENTAHO
        geraDiretorio
        cd /home
        if [ -d pentaho ] ; then

            tar czpf $bkpDiretorio/pentaho.tar.gz pentaho
            cd $bkpDiretorio
            cd ..
            arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
            tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
            mv $arquivoBkp $destinoBkp
        fi

}


backupSite(){

    qtd=0

    if [ -d /comunix ] ; then

        tipo=SITE
        cd /comunix/
        rst=`echo $?`
        if [ $rst -eq 0 ] ; then
            listaSite=$(ls -d */)
            geraDiretorio
            for i in $listaSite ; do
                i=`echo $i|sed 's/\///g'`
                if [ -d $i ] && [ ! -h $i ] ; then
                        if [ -d $i/core/public/temp ] ; then
                                rm $i/core/public/temp/*
                        fi
                        tar cpf $bkpDiretorio/$i.tar $i
                        qtd=`expr $qtd + 1`
                fi
            done
            cd $bkpDiretorio
            cd ..
            if [ $qtd -gt 0 ] ; then
                arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
                tar czpf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
                mv $arquivoBkp $destinoBkp
            fi
        fi
    fi

}

dumpDB(){

    if [ -x /usr/local/pgsql/bin/pg_dump ] && [ -x /usr/local/pgsql/bin/psql ] ; then

        tipo=DB
        endBanco="/usr/local/pgsql/bin/"
        qtdLinha=$($endBanco./psql -U postgres -l | awk -F "|" '{print $1}'|grep "(" | cut -f2 -d "("|cut -f1 -d " ")
        listaDataBase=$($endBanco./psql -U postgres -l |grep -v ":" | awk -F "|" '{print $1}'|grep -A $qtdLinha "-"|grep -v "-")
        geraDiretorio
        cd $bkpDiretorio
        for i in $listaDataBase ; do
            if [ "$i" != template0 ] && [ "$i" != template1 ] && [ "$i" != postgres ] ; then

		versionPostgresql=`/usr/local/pgsql/bin/./psql -U postgres -c "SELECT lower(REPLACE(SUBSTR(VERSION(),1,14),'.',''))" |grep postgresql |cut -d" " -f3`

		if [ $versionPostgresql -ge 92 ] ; then
			$endBanco./pg_dump $i -U postgres --exclude-table-data=analitico.dim_intervalo --exclude-table-data=analitico.dim_tempo \
				--exclude-table-data=analitico.th_chamada_dia_servico --exclude-table-data=discador.tc_modify_bilhetador \
				--exclude-table-data=discador.tc_modify_chamada --exclude-table-data=discador.tc_modify_ura \
				--exclude-table-data=discador.tc_modify_ura_verbio --exclude-table-data=public.realtime_abandonadas_cdr \
				--exclude-table-data=public.realtime_atendidas_cdr --exclude-table-data=public.realtime_opcao_fila \
				--exclude-table-data=public.tb_agente_registro_web --exclude-table-data=public.tb_real_agente \
				--exclude-table-data=public.tb_real_agente_5 --exclude-table-data=public.tb_real_agente_a \
				--exclude-table-data=public.tb_real_agente_b --exclude-table-data=public.tb_real_agente_c \
				--exclude-table-data=public.tb_real_agente_d --exclude-table-data=public.tb_real_agente_equipe_5 \
				--exclude-table-data=public.tb_real_agente_equipe_lista --exclude-table-data=public.tb_real_agente_evento \
				--exclude-table-data=public.tb_real_agente_geral_lista --exclude-table-data=public.tb_real_agente_online_10s \
				--exclude-table-data=public.tb_real_agente_online_equipe_10s --exclude-table-data=public.tb_real_agente_online_servico_10s \
				--exclude-table-data=public.tb_real_agente_servico_5 --exclude-table-data=public.tb_real_agente_servico_lista \
				--exclude-table-data=public.tb_real_campanha_cdr --exclude-table-data=public.tb_real_chamadas_equipe \
				--exclude-table-data=public.tb_real_chamadas_geral --exclude-table-data=public.tb_real_chamadas_geral_nivel_primario \
				--exclude-table-data=public.tb_real_chamadas_geral_nivel_secundario --exclude-table-data=public.tb_real_chamadas_servico \
				--exclude-table-data=public.tb_real_chamadas_servico_nivel_primario \
				--exclude-table-data=public.tb_real_chamadas_servico_nivel_secundario --exclude-table-data=public.tb_real_desempenho_5 \
				--exclude-table-data=public.tb_real_estatistica_agente_equipe_5 --exclude-table-data=public.tb_real_estatistica_agente_geral_5 \
				--exclude-table-data=public.tb_real_estatistica_agente_servico_5 --exclude-table-data=public.tb_real_estatistica_chamada_5 \
				--exclude-table-data=public.tb_real_estatistica_nivel_primario_5 --exclude-table-data=public.tb_real_estatistica_nivel_secundario_5 \
				--exclude-table-data=public.tb_real_fila_espera --exclude-table-data=public.tb_real_fila_espera_10s \
				--exclude-table-data=public.tb_real_status_agente --exclude-table-data=public.tb_real_status_equipe \
				--exclude-table-data=public.tb_real_status_geral --exclude-table-data=public.tb_real_status_servico \
				--exclude-table-data=suporte.* | gzip > bkp_DATABASE_COMPLETE_"$i"_"$data".gz
				$endBanco./pg_dump $i -U postgres --exclude-table-data=analitico.dim_intervalo --exclude-table-data=analitico.dim_tempo \
				--exclude-table-data=analitico.th_chamada_dia_servico --exclude-table-data=discador.tb_correspondencia \
				--exclude-table-data=discador.tc_modify_bilhetador --exclude-table-data=discador.tc_modify_chamada \
				--exclude-table-data=discador.tc_modify_ura --exclude-table-data=discador.tc_modify_ura_verbio \
				--exclude-table-data=discador.th_correspondencia_report --exclude-table-data=extendchat.tb_chat_atendimento \
				--exclude-table-data=extendfax.th_fax --exclude-table-data=extendfax.th_fax_status_agente --exclude-table-data=extendfax.tl_fax_agente \
				--exclude-table-data=extendfax.tl_fax_trans_status --exclude-table-data=personalizado.tb_poupatempo_origem_registro \
				--exclude-table-data=personalizado.tb_poupatempo_poupatempo --exclude-table-data=personalizado.tb_poupatempo_servico_agendamento \
				--exclude-table-data=personalizado.tb_poupatempo_tipo_documentacao --exclude-table-data=personalizado.tb_poupatempo_tipo_servico_agendamento \
				--exclude-table-data=personalizado.th_poupatempo_agendamento --exclude-table-data=personalizado.th_poupatempo_documentacao \
				--exclude-table-data=public.abandonadas_cdr --exclude-table-data=public.atendidas_cdr --exclude-table-data=public.erro_site \
				--exclude-table-data=public.link_grav_cdr --exclude-table-data=public.link_grav_video_cdr --exclude-table-data=public.opcao_fila \
				--exclude-table-data=public.realtime_abandonadas_cdr --exclude-table-data=public.realtime_atendidas_cdr \
				--exclude-table-data=public.realtime_opcao_fila --exclude-table-data=public.tb_aca_dia_util --exclude-table-data=public.tb_aca_domingo \
				--exclude-table-data=public.tb_aca_sabado --exclude-table-data=public.tb_agente_registro_web \
				--exclude-table-data=public.tb_ativo_automatico --exclude-table-data=public.tb_ativo_campanha_automatico \
				--exclude-table-data=public.tb_ativo_status_automatico --exclude-table-data=public.tb_real_agente \
				--exclude-table-data=public.tb_real_agente_5 --exclude-table-data=public.tb_real_agente_a \
				--exclude-table-data=public.tb_real_agente_b --exclude-table-data=public.tb_real_agente_c --exclude-table-data=public.tb_real_agente_d \
				--exclude-table-data=public.tb_real_agente_equipe_5 --exclude-table-data=public.tb_real_agente_equipe_lista \
				--exclude-table-data=public.tb_real_agente_evento --exclude-table-data=public.tb_real_agente_geral_lista \
				--exclude-table-data=public.tb_real_agente_online_10s --exclude-table-data=public.tb_real_agente_online_equipe_10s \
				--exclude-table-data=public.tb_real_agente_online_servico_10s --exclude-table-data=public.tb_real_agente_servico_5 \
				--exclude-table-data=public.tb_real_agente_servico_lista --exclude-table-data=public.tb_real_campanha_cdr \
				--exclude-table-data=public.tb_real_chamadas_equipe --exclude-table-data=public.tb_real_chamadas_geral \
				--exclude-table-data=public.tb_real_chamadas_geral_nivel_primario --exclude-table-data=public.tb_real_chamadas_geral_nivel_secundario \
				--exclude-table-data=public.tb_real_chamadas_servico --exclude-table-data=public.tb_real_chamadas_servico_nivel_primario \
				--exclude-table-data=public.tb_real_chamadas_servico_nivel_secundario --exclude-table-data=public.tb_real_desempenho_5 \
				--exclude-table-data=public.tb_real_estatistica_agente_equipe_5 --exclude-table-data=public.tb_real_estatistica_agente_geral_5 \
				--exclude-table-data=public.tb_real_estatistica_agente_servico_5 --exclude-table-data=public.tb_real_estatistica_chamada_5 \
				--exclude-table-data=public.tb_real_estatistica_nivel_primario_5 --exclude-table-data=public.tb_real_estatistica_nivel_secundario_5 \
				--exclude-table-data=public.tb_real_fila_espera --exclude-table-data=public.tb_real_fila_espera_10s \
				--exclude-table-data=public.tb_real_status_agente --exclude-table-data=public.tb_real_status_equipe \
				--exclude-table-data=public.tb_real_status_geral --exclude-table-data=public.tb_real_status_servico \
				--exclude-table-data=public.th_agente_5 --exclude-table-data=public.th_agente_associacao \
				--exclude-table-data=public.th_agente_equipe_5 --exclude-table-data=public.th_agente_evento \
				--exclude-table-data=public.th_agente_online_10s --exclude-table-data=public.th_agente_online_equipe_10s \
				--exclude-table-data=public.th_agente_online_evento --exclude-table-data=public.th_agente_online_servico_10s \
				--exclude-table-data=public.th_agente_servico_5 --exclude-table-data=public.th_bilhetador --exclude-table-data=public.th_bilhete_evento \
				--exclude-table-data=public.th_callback --exclude-table-data=public.th_fila_espera_10s \
				--exclude-table-data=public.th_registro_utilizacao_d --exclude-table-data=public.th_rel_agente_online_5 \
				--exclude-table-data=public.th_rel_agente_online_servico_5 --exclude-table-data=public.th_rel_atividade_agente_5 \
				--exclude-table-data=public.th_rel_desempenho_5 --exclude-table-data=public.th_rel_desempenho_d \
				--exclude-table-data=public.th_rel_estatistica_agente_5 --exclude-table-data=public.th_rel_estatistica_agente_d \
				--exclude-table-data=public.th_rel_estatistica_agente_servico_5 --exclude-table-data=public.th_rel_estatistica_agente_servico_d \
				--exclude-table-data=public.th_rel_estatistica_chamada_5 --exclude-table-data=public.th_rel_estatistica_chamada_d \
				--exclude-table-data=public.th_rel_estatistica_lista_agente_servico_5 --exclude-table-data=public.th_rel_estatistica_nivel_primario_5 \
				--exclude-table-data=public.th_rel_estatistica_nivel_primario_d --exclude-table-data=public.th_rel_estatistica_nivel_secundario_5 \
				--exclude-table-data=public.th_rel_estatistica_nivel_secundario_d --exclude-table-data=public.th_rel_estatistica_ura_5 \
				--exclude-table-data=public.th_sox_information_avi --exclude-table-data=public.th_sox_information_mp3 \
				--exclude-table-data=public.th_sox_information_wav --exclude-table-data=public.th_status_agente --exclude-table-data=public.th_ura \
				--exclude-table-data=public.th_ura_verbio --exclude-table-data=public.tl_aplicacao --exclude-table-data=public.tr_agente_evento_atendidas_cdr \
				--exclude-table-data=public.tr_agente_evento_campanha_cdr --exclude-table-data=public.tr_agente_evento_equipe \
				--exclude-table-data=public.tr_agente_evento_servico --exclude-table-data=public.tr_alarme_evento \
				--exclude-table-data=public.tr_atendida_equipe --exclude-table-data=public.tr_callback --exclude-table-data=public.tr_campanha_equipe \
				--exclude-table-data=public.tr_real_agente_evento_atendidas_cdr --exclude-table-data=public.tr_real_agente_evento_equipe \
				--exclude-table-data=public.tr_real_agente_evento_servico --exclude-table-data=public.tr_real_atendida_equipe \
				--exclude-table-data=public.tr_real_campanha_equipe --exclude-table-data=public.tr_real_status_agente_campanha \
				--exclude-table-data=public.tr_real_status_agente_equipe --exclude-table-data=public.tr_real_status_agente_servico \
				--exclude-table-data=public.tr_status_agente_campanha --exclude-table-data=public.tr_status_agente_equipe \
				--exclude-table-data=public.tr_status_agente_servico --exclude-table-data=realtime.th_matriz_bilhetador \
				--exclude-table-data=realtime.th_matriz_chamada --exclude-table-data=realtime.th_matriz_evento_agente \
				--exclude-table-data=realtime.th_matriz_ura --exclude-table-data=suporte.* \
				--exclude-table-data=ura.th_ura | gzip > bkp_DATABASE_SPEED_"$i"_"$data".gz
		else
			$endBanco./pg_dump $i -U postgres  | gzip > bkp_DATABASE_COMPLETE_"$i"_"$data".gz
		fi
	    fi
        done
        cd ..
            arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar
        tar cf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
        mv $arquivoBkp $destinoBkp
    fi
}


dumpDbMysql(){

    if [ -x /usr/bin/mysql ] ; then

            usuario="root"
            senha="123456"

            tipo=DBmysql
            endBanco="/usr/bin/"
            listaDataBase=$($endBanco./mysql -u $usuario -p$senha -e "show databases;" |grep -v +)
            geraDiretorio
            cd $bkpDiretorio
            for i in $listaDataBase ; do
                    if [ "$i" != Database ] && [ "$i" != information_schema ] && [ "$i" != mysql ] ; then
                            $endBanco./mysqldump $i -u $usuario -p$senha | gzip > bkp_DATABASE_"$i"_"$data".gz
                    fi
            done
            cd ..
            arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar
            tar cf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
            mv $arquivoBkp $destinoBkp
    fi
}

descobreDia(){
    numdia=$(date +%w -d ' -1 day')
    case $numdia in
        0) dia=domingo ;;
        1) dia=segunda ;;
        2) dia=terca ;;
        3) dia=quarta ;;
        4) dia=quinta ;;
        5) dia=sexta ;;
        6) dia=sabado ;;
    esac

}
enviaFtp(){

#Dados FTP
bkpServer='192.168.0.202'
ftpDirBkp=bkpComunix
conta=comunix
senha=123456

cd $destinoBkp

ftp -in $bkpServer <<EOF
  quote user $conta
  quote pass $senha
  cd bkpComunix
  mdelete bkp_"$nomeServidor"_"$tipo"*
  put $arquivoBkp
  bye
EOF

}

enviaSMB(){

nomeCliente=bkp_sebrae
dirShare=//10.11.195.28/backupconf
dirDestino=/mnt/share
cpDestino=/mnt/share
usuario=suporte
senha=sp.call@Suporte

if [ ! -d $dirDestino ] ; then
        mkdir -p $dirDestino
        mount -t cifs $dirShare $dirDestino -o username=$usuario,password=$senha 2> /dev/null
        montou=$(echo $?)
        else
                umount $dirDestino 2> /dev/null
                mount -t cifs $dirShare $dirDestino -o username=$usuario,password=$senha 2> /dev/null
                montou=$(echo $?)
fi

  if [ $montou == 0 ] ; then
    mkdir -p $dirDestino

    if [ -d $dirDestino ] ; then
      mkdir -p $cpDestino/$nomeCliente
      cp -Ru $destinoBkp/$arquivoBkp $cpDestino/$nomeCliente/$arquivoBkp 2> /dev/null
      if [ -f $cpDestino/$nomeCliente/$arquivoBkp ] ; then
                umount $dirDestino 2> /dev/null
        cd $destinoBkp
                ls bkp_"$nomeServidor"_"$tipo"* >> log_backup.txt 2> /dev/null
          else
                  echo "falha ao copiar o backup do $data" >> /tmp/log_bkp_"$nomeServidor".txt
          umount $dirDestino 2> /dev/null
      fi
    else
       echo "nao existe o diretorio $data" >> /tmp/log_bkp_diretorio_"$nomeServidor".txt
       umount $dirDestino 2> /dev/null
    fi
    else
        echo "falha ao montar compartilhamento $data" >> /tmp/log_bkp_compartilhamento_"$nomeServidor".txt
  fi
}

asteriskCfgBkp(){
    if [ -x /usr/sbin/asterisk ] ; then 

        tipo=CFG
        geraDiretorio
        #Fazendo backup das configuracoes do asterisk no etc
        cd /etc/
        arquivoTar="$bkpDiretorio"/"$data"_etc_asterisk_"$nomeServidor".tar.gz
        tar zcf $arquivoTar asterisk 2> /dev/null
        #Fazendo backup dos arquivos no /var/lib/asterisk
        cd /var/lib/
        arquivoTar="$bkpDiretorio"/"$data"_var_lib_asterisk_"$nomeServidor".tar.gz
        tar zcf $arquivoTar asterisk 2> /dev/null
        cd /usr/lib/
        arquivoTar="$bkpDiretorio"/"$data"_usr_lib_asterisk_"$nomeServidor".tar.gz
        tar zcf $arquivoTar asterisk 2> /dev/null
        cd /home/
        tar czpf $bkpDiretorio/home_extend.tar.gz --exclude='calls' --exclude='ramais' --exclude='gravacoes' --exclude='queue' --exclude='join' --exclude='verbio' extend
        cd $diretorioTemp
        arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
        tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
        mv $arquivoBkp $destinoBkp
    fi
}
comunixCfgBkp(){
    if [ -x /usr/sbin/comunix ] ; then 

        tipo=CFG
        geraDiretorio
        #Fazendo backup das configuracoes do comunix no etc
        cd /etc/
        arquivoTar="$bkpDiretorio"/"$data"_etc_comunix_"$nomeServidor".tar.gz
        tar zcf $arquivoTar comunix 2> /dev/null
        #Fazendo backup dos arquivos no /var/lib/comunix
        cd /var/lib/
        arquivoTar="$bkpDiretorio"/"$data"_var_lib_comunix_"$nomeServidor".tar.gz
        tar zcf $arquivoTar comunix 2> /dev/null
        cd /usr/lib/
        arquivoTar="$bkpDiretorio"/"$data"_usr_lib_comunix_"$nomeServidor".tar.gz
        tar zcf $arquivoTar comunix 2> /dev/null
        cd /home/
        tar czpf $bkpDiretorio/home_extend.tar.gz --exclude='calls' --exclude='ramais' --exclude='gravacoes' --exclude='queue' --exclude='join' --exclude='verbio' extend
        cd $diretorioTemp
        arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
        tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
        mv $arquivoBkp $destinoBkp
    fi
}
extendphoneBkp(){
    if [ -d /home/extendphone ] ; then 

        tipo=EXTPHONE
        geraDiretorio
        #Fazendo backup das configuracoes do extendphone
        cd /home/
        arquivoTar="$bkpDiretorio"/"$data"_home_extendphone_"$nomeServidor".tar.gz
        tar zcf $arquivoTar --exclude='*.pdf' --exclude='*.tiff' extendphone 2> /dev/null
        cd $diretorioTemp
        arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
        tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
        mv $arquivoBkp $destinoBkp
    fi
}

backupShare(){

    tipo=SHARE
        geraDiretorio
        cd /home/
        if [ -d extend ] ; then
                tar --no-recursion -czpf $bkpDiretorio/home_extend.tar.gz extend/*
                cd extend/calls
	        rst=`echo $?`
		if [ "$backupCallsBackup" = "true" ] ; then
        		if [ $rst -eq 0 ] ; then
                		dataAtual=`date +%F`
				if [ -d $destinoBkp ] ; then
					cd backup
					rst=`echo $?`
					if [ $rst -eq 0 ] ; then
                				listaDias=`ls`
                				for i in $listaDias ; do
                        				if [ "$i" != "$dataAtual" ] ; then
                                				tar uvf $destinoBkp/backupShareCallsBackup.tar $i
                        				fi
                				done
					fi
					cd ..
				fi
        		fi
		else
			echo "skip function backupCallsBackup"
		fi
                cd $bkpDiretorio
                cd ..
                rst=`echo $?`
                if [ $rst -eq 0 ] ; then
                        arquivoBkp=bkp_"$nomeServidor"_"$tipo"_"$data".tar.gz
                        tar zcf $arquivoBkp bkp_"$nomeServidor" 2> /dev/null && rm -r $bkpDiretorio
                        mv $arquivoBkp $destinoBkp
                else
                        rm -r $bkpDiretorio
                fi
        fi
}

checkFile(){
	arq="$i"
	cont=0
	check=true
	while $check ; do
		if [ -f $dir/$arq.bz2 ] ; then
			echo "Existe $dir/$arq.bz2"
			arq="$i.$cont"
			cont=`expr $cont + 1`
		else
			echo "Gerando: $arq.bz2"
			sleep 2
			i="$arq"
			check=false
		fi
		sleep 5
	done
}

backupLogExtend(){

dir=/home/extend/log

if [ -d $dir ] ; then
	cd $dir
	rst=`echo $?`
	listaFiles=$(ls|grep -v .bz2 |grep 20 |grep -v `date +%Y_%m_%d -d ' 0 day'`|grep -v `date +%Y_%m_%d -d ' -1 day'`|grep -v `date +%Y_%m_%d -d ' -2 day'`|grep -v `date +%Y%m%d -d ' 0 day'`|grep -v `date +%Y%m%d -d ' -1 day'`|grep -v `date +%Y%m%d -d ' -2 day'`)
	for i in $listaFiles ; do
		if [ -x /bin/bzip2 ] ; then
			echo "Compactando ... $i"
			y=$i
			checkFile
			cat $y|bzip2 --best -v > $i.bz2 && rm -v $y
			sleep 2
		else
			echo "o bzip2 não está instalado."
		fi
	done
fi
}


geraDiretorio(){

    cd /usr/src
    if [ ! -d $bkpDiretorio ] ; then
            mkdir $bkpDiretorio
    else
            rm -r $bkpDiretorio 2> /dev/null
            mkdir $bkpDiretorio
    fi
    cd -
}
verificaDirBackup(){

    if [ ! -d $destinoBkp ] ; then
            mkdir -p $destinoBkp
    fi
}

#Descobrindo a data
#descobreDia
#Verificando o diretorio de backup
verificaDirBackup

#Fazendo chamada das funcoes!

#Fazendo backup dos sites
#backupSite
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup das databases
#dumpDB
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup das config asterisk
asteriskCfgBkp
qtdBkp=5
checkQtdBackup
#enviaFtp
enviaSMB

#Fazendo o backup das config comunix
#comunixCfgBkp
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup do bando mysql
#dumpDbMysql
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup do SVN
#backupSvn
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o bakcup Pentaho
#backupPentaho
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o bakcup Share
#backupCallsBackup=true
#backupShare
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup das config extendphone
#extendphoneBkp
#qtdBkp=5
#checkQtdBackup
#enviaFtp
#enviaSMB

#Fazendo o backup dos logs de /home/extend/log
backupLogExtend
