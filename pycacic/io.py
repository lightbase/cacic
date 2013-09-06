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


    Modulo io
    
    Modulo com finalidade de controlar a comunicacao
    do agente local com o servidor, Gerente Web. 
    
    @author: Dataprev - ES
    
"""
import re
import sys, os

from xml.dom import minidom, Node
from globals import Globals

try:
    from coletores.lib.ccrypt import CCrypt
except:
    from ccrypt import CCrypt

class IOConfig:
    """
        Classe IOConfig
        
        Responsavel por guardar o caminho do arquivo de
        configuracao do PyCacic e efetuar a leitura dos nos principais
        dele.        
    """

    FILE = '%s/config/cacic.dat' % Globals.PATH
    
    def exists():
        """Retorna se um arquivo existe ou nao"""
        return (os.path.exists(IOConfig.FILE))
    
    def getFile(file):
        """
            Retorna o conteudo do arquivo de configuracao
            caso nao exista gera uma excecao
        """
        if not os.path.exists(file):
            raise Exception('Arquivo de configuração não encontrado.')
        else:
            return open(file, 'r').read()
        
    def getDecryptedFile():
        """
            Retorna o conteudo do arquivo de configuracao
            caso nao exista gera uma excecao
        """
        cipher = CCrypt()
        return cipher.decrypt(IOConfig.getFile(IOConfig.FILE))
    
    def encryptFile(config):
        cipher = CCrypt()
        return cipher.encrypt(config)

    def getRoot():
        """Retorna o node principal do XML"""
        if not IOConfig.exists():
            raise Exception('Arquivo de configuração não encontrado.')
        else:
            f = open(IOConfig.FILE)
            xmlstr = f.read()
            f.close()
            
            cipher = CCrypt()
            xmlstr = cipher.decrypt(xmlstr)
            
            xml = minidom.parseString(xmlstr)
            root = xml.getElementsByTagName('config')[0]
            return root
        
    def getServer():
        """Retorna o node do server"""
        root = IOConfig.getRoot()
        return root.getElementsByTagName('server')[0]
    
    def getUpdate():
        """Retorna o node do server"""
        root = IOConfig.getRoot()
        return root.getElementsByTagName('update')[0]
    
    def getColetores():
        """Retorna o node dos coletores"""
        root = IOConfig.getRoot()
        return root.getElementsByTagName('coletores')[0]
    
    def getPycacic():
        """Retorna o node do Pycacic"""
        root = IOConfig.getRoot()
        return root.getElementsByTagName('pycacic')[0]
    
    def getPycacicStatus():
        """Retorna o node de status"""
        pycacic = IOConfig.getPycacic()
        for no in pycacic.childNodes:
            if no.nodeType == Node.ELEMENT_NODE and no.nodeName == 'status':
                return no
    
    def getSocket():
        """Retorna o node de socket"""
        root = IOConfig.getRoot()
        return root.getElementsByTagName('socket')[0]
    
    exists = staticmethod(exists)
    getFile = staticmethod(getFile)
    getDecryptedFile = staticmethod(getDecryptedFile)
    encryptFile = staticmethod(encryptFile)
    getRoot = staticmethod(getRoot)
    getServer = staticmethod(getServer)
    getUpdate = staticmethod(getUpdate)
    getColetores = staticmethod(getColetores)
    getPycacic = staticmethod(getPycacic)
    getPycacicStatus = staticmethod(getPycacicStatus)
    getSocket = staticmethod(getSocket)

class Reader:
    """
        Classe Reader
        
        Responsavel por efetuar a leitura do arquivo de configuracao
        do PyCacic. Informando dados como endereco do Gerente Web,
        usuario e senha, etc.
        
    """

    def getServer():
        """Retorna um dicionario contendo o endereco do servidor de aplicacao"""
        server = {'address':'', 'page': '', 'agent':'', 'username':'', 'password':''}
        config = IOConfig.getServer()
        for no in config.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                server[no.nodeName] = no.firstChild.nodeValue                
        return server
    
    def getUpdate():
        """Retorna um dicionario contendo o endereco do servidor de update"""
        server = {'address' : '', 'port' : '', 'path': '', 'username': '', 'password': ''}
        config = IOConfig.getUpdate()
        for no in config.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                server[no.nodeName] = no.firstChild.nodeValue                
        return server
    
    def getColetor(id):
        """
            Retorna um dicionario contendo as informacoes do
            coletor especificado por parametro
        """
        coletor = {'id':'','page':''}
        cols = IOConfig.getColetores()
        for no in cols.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                if no.nodeName == 'coletor' and no.attributes['id'].nodeValue == id:
                    coletor['id'] = no.attributes['id'].nodeValue
                    coletor['page'] = no.attributes['page'].nodeValue
                    return coletor
        return coletor

    def getPycacic():
        """Retorna um dicionario contendo as informacoes sobre o PyCacic"""
        pycacic = {'path' : '', 'hash' : '', 'password' : '', 'locale' : '', 'version' : '0.0.1.-1'}
        config = IOConfig.getPycacic()
        for no in config.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                pycacic[no.nodeName] = no.firstChild.nodeValue                
        return pycacic
    
    def getPycacicStatus(id):
        """
            Retorna um dicionario contendo as informacoes de
            status (param) especificado por parametro
        """
        status = {'id' : '', 'value' : ''}
        sts = IOConfig.getPycacicStatus()
        for no in sts.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                if no.nodeName == 'param' and no.attributes['id'].nodeValue == id:
                    status['id'] = no.attributes['id'].nodeValue
                    status['value'] = no.attributes['value'].nodeValue
                    return status
                
    def getSocket():
        """Retorna um dicionario contendo as informacoes de socket"""
        socket = {'host' : '', 'port' : '', 'buffer' : ''}
        sock = IOConfig.getSocket()
        for no in sock.childNodes:
            if no.nodeType == Node.ELEMENT_NODE:
                socket[no.nodeName] = no.firstChild.nodeValue
        return socket
    
    
    getServer = staticmethod(getServer)
    getUpdate = staticmethod(getUpdate)    
    getPycacic = staticmethod(getPycacic)
    getColetor = staticmethod(getColetor)
    getPycacicStatus = staticmethod(getPycacicStatus)
    getSocket = staticmethod(getSocket)    
    
class Writer:
    """
        Classe Write
        
        Responsavel por efetuar a escrita no arquivo de configuracao
        do PyCacic. Alterando dados como se e instalacao, endereco do
        Gerente Web, usuario e senha, etc.        
    """
    
    def saveXML(xml, file = ''):
        """Encripta e Salva o XML de configuracoes"""
        Writer.saveNotEncryptedXML(IOConfig.encryptFile(xml), file)
            
    def saveNotEncryptedXML(xml, file = ''):
        """Salva o XML (nao encriptado) de configuracoes"""
        try:
            if file == '':
                file = IOConfig.FILE
            f = open(file, 'w')
            f.write(xml)
            f.close()
        except IOError, e:
            raise IOError(e)
    
    def setNodeValue(node, value):
        """Altera o valor do node passado por parametro"""
        re_open = re.compile('<.+?>')
        re_close = re.compile('</.*>')
        open = re_open.findall(node)[0]
        close = re_close.findall(node)[0]
        return '%s%s%s' % (open, value, close)
    
    def setNodeAttrib(node, attrib, value):
        """Altera o valor do atributo do node passado por parametro"""
        re_att = re.compile('%s=".*?"' % attrib)
        old = re_att.findall(node)[0]        
        return node.replace(old, ('%s="%s"' % (attrib, value)))

    def setServer(node, value, config = '', enc = True):
        """Altera o no especificado das informacoes do servidor aplicação"""
        configfile = ''
        if config == '':
            config = IOConfig.getDecryptedFile()
        else:
            configfile, config = config, IOConfig.getFile(config)
        re_sv = re.compile('<server(?:.|\n)*</server>')
        re_node = re.compile('<%s.*</%s>' % (node, node))
        sv = re_sv.findall(config)[0]        
        if len(re_node.findall(sv)) == 0:
            return 0 # False
        node = re_node.findall(sv)[0]        
        server = sv
        server = server.replace(node, Writer.setNodeValue(node, value))
        if enc:
            Writer.saveXML(config.replace(sv, server), configfile)
        else:
            Writer.saveNotEncryptedXML(config.replace(sv, server), configfile)
            
    def setUpdate(node, value, config = '', enc = True):
        """Altera o no especificado das informacoes do servidor de ftp"""
        configfile = ''
        if config == '':
            config = IOConfig.getDecryptedFile()
        else:
            configfile, config = config, IOConfig.getFile(config)
        re_sv = re.compile('<update(?:.|\n)*</update>')
        re_node = re.compile('<%s.*</%s>' % (node, node))
        sv = re_sv.findall(config)[0]        
        if len(re_node.findall(sv)) == 0:
            return 0 # False
        node = re_node.findall(sv)[0]        
        server = sv
        server = server.replace(node, Writer.setNodeValue(node, value))
        if enc:
            Writer.saveXML(config.replace(sv, server), configfile)
        else:
            Writer.saveNotEncryptedXML(config.replace(sv, server), configfile)
        
    def setPycacic(node, value, config = '', enc = True):
        configfile = ''
        if config == '':
            config = IOConfig.getDecryptedFile()
        else:
            configfile, config = config, IOConfig.getFile(config)
        re_pc = re.compile('<pycacic(?:.|\n)*</pycacic>')
        re_node = re.compile('<%s.*</%s>' % (node, node))
        pc = re_pc.findall(config)[0]
        if len(re_node.findall(pc)) == 0:
            return 0 # False
        node = re_node.findall(pc)[0]
        pycacic = pc
        pycacic = pycacic.replace(node, Writer.setNodeValue(node, value))
        if enc:
            Writer.saveXML(config.replace(pc, pycacic), configfile)
        else:
            Writer.saveNotEncryptedXML(config.replace(pc, pycacic), configfile)
        
    def setPycacicStatus(s, v):
        """Modifica o status do Pycacic"""
        config = IOConfig.getFile()
        re_pc = re.compile('<pycacic(?:.|\n)*</pycacic>')
        re_st = re.compile('<status(?:.|\n)*</status>')
        re_pr = re.compile('<param.*?id="'+s+'".*?/>')
        pc = re_pc.findall(config)[0]
        st = re_st.findall(config)[0]
        pr = re_pr.findall(config)[0]
        status = st
        pycacic = pc
        values = ("no", "yes")
        status = status.replace(pr, Writer.setNodeAttrib(pr, "value", values[bool(v)]))
        pycacic = pycacic.replace(st, status)
        Writer.saveXML(config.replace(pc, pycacic))

    saveXML = staticmethod(saveXML)
    saveNotEncryptedXML = staticmethod(saveNotEncryptedXML)
    setServer = staticmethod(setServer)
    setPycacic = staticmethod(setPycacic)
    setUpdate = staticmethod(setUpdate)
    setPycacicStatus = staticmethod(setPycacicStatus)
    setNodeValue = staticmethod(setNodeValue)
    setNodeAttrib = staticmethod(setNodeAttrib)
    