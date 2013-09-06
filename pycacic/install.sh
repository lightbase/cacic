#!/bin/bash
# 19/04/2011
#
# pyCACIC installer
#
# Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil
#   
# Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais
#    
# O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
# publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
# 
# Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
# MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
# 
# Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
# Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#

if [[ $EUID -ne 0 ]]; then
	echo "A instalacaprecisa ser executada como root"
	exit 1
fi
progdir=$( 
  cd -P -- "$(dirname -- "$0")" && 
    pwd -P 
)


cmd_found()
{
	type $1 > /dev/null 2>&1;
}

install_python()
{
	if (cmd_found apt-get) then
		apt-get install python
	else 
		if (cmd_found yum) then
			yum install python
		else
			echo "ERRO: Python nao instalado! "
			exit
		fi
	fi
}

install_cacic()
{
	# rename install crypt file
	#mv $progdir/ccrypt.pycomp $progdir/ccrypt.pyc > /dev/null 2>&1
	
	tar -xf $progdir/cacic.tar -C /usr/share
	
	#rename final crypt files
	#mv /usr/share/pycacic/coletores/lib/ccrypt.pycomp /usr/share/pycacic/coletores/lib/ccrypt.pyc > /dev/null 2>&1
	
	python $progdir/setservice.py
	
}

start_cacic()
{
	echo -n "Iniciando pyCACIC... "
	(python /usr/share/pycacic/cacic.py > /dev/null 2>&1)&
	echo "[OK]"
}

scpwd=`pwd`

echo -ne "Verificando Python... "
if !(cmd_found python) then
	echo ""
	install_python
else
	echo "[OK]"
fi
install_cacic;
start_cacic
chmod 0755 /usr/share/pycacic/gui_pycacic.py
chmod 0755 /usr/share/pycacic/cacic.py
