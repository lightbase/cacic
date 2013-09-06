# -*- coding: UTF-8 -*-

"""

    Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil
    
    Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais
    
    O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
    publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
    
    Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
    
    Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

"""

import os
import codecs
import commands
import traceback
from time import strftime

class CLog:
    
    STRP = '%CLOG%'
    DIR = '/tmp/pyCACIC_Packages_Generator_Logs/'    
       
    def getCurrentFileName():
        """Retorna o nome do arquivo de log atual, formato YYYY-MM-DD"""
        return '%s.log' % strftime("%Y-%m-%d")
    
    def getCurrentFile():
        """Retorna o conteudo do arquivo de log atual"""
        path = CLog.DIR + CLog.getCurrentFileName()
        if os.path.exists(path):
            return file(path, 'r').read()
        return ''
    
    def removeOlds():
        """Remove os arquivos de log que nao sao do mes atual"""
        name = CLog.getCurrentFileName().split('-')
        if not len(name) == 3:
            return
        month = name[1]
        #commands.getoutput('rm -- *-!(%s)-*.log' % month)
        #commands.getoutput('rm -- /usr/share/pycacic/logs/*.log')
    
    def createNew(fileName):
        """Cria novo arquivo de log"""
        if not os.path.exists(CLog.DIR):
            os.mkdir(CLog.DIR)
        file(fileName, 'w').write('')
        codecs.open(fileName, "w", "utf-8").write('')
    
    def appendLine(module, desc):
        """Adiciona uma linha contendo o data, nome do modulo e a descricao da acao no final """
        fileName = DIR+'%s' % CLog.getCurrentFileName()
        if not os.path.exists(fileName):
            CLog.removeOlds()
            CLog.createNew(fileName)
        f = file(fileName, 'a')
        l =  '%s%s%s%s%s\n' % (strftime("%H:%M:%S %d/%m/%Y"), CLog.STRP, module, CLog.STRP, desc)
        f.write(l.encode("utf-8"))
        f.close()
        
    def appendException(e, module, desc, data = ""):
        """Adiciona uma linha contendo o data, nome do modulo e a descricao da acao no final """
        fileName = DIR+'exceptions.log'
        if not os.path.exists(fileName):
            CLog.createNew(fileName)
        f = file(fileName, 'a')
        l =  '* %s\t%s\t%s\n' % (strftime("%H:%M:%S %d/%m/%Y"), module, desc)
        f.write(l.encode("utf-8"))
        if data != "":
            f.write("\n\nData:\n".encode("utf-8"))
            f.write(data)
            f.write("\n\n".encode("utf-8"))
        f.write(traceback.format_exc().encode("utf-8"))
        f.write("\n\n\n".encode("utf-8"))
        f.close()
        
    getCurrentFileName = staticmethod(getCurrentFileName)
    getCurrentFile = staticmethod(getCurrentFile)
    removeOlds = staticmethod(removeOlds)
    createNew = staticmethod(createNew)
    appendLine = staticmethod(appendLine)
    appendException = staticmethod(appendException)
    
