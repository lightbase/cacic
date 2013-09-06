#!/bin/bash
# 13/05/2008
#
# PyCacic installer
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
	echo "A instalacao precisa ser executada como root"
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
			echo "ERRO: Python NAO INSTALADO!!?? "
			exit
		fi
	fi
}

install_cacic()
{
	python $progdir/setservice.py
	
}

scpwd=`pwd`

echo -ne "Checando existencia do Python... "
if !(cmd_found python) then
	echo ""
	install_python
else
	echo "[OK]"
fi
install_cacic
