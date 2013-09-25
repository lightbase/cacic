from sqlalchemy import *
from sqlalchemy.ext.declarative import declarative_base

from pyramid.config import Configurator
from pyramid_restler.model import SQLAlchemyORMContext

from sqlalchemy.orm import (
    scoped_session,
    sessionmaker,
    mapper
    )

from zope.sqlalchemy import ZopeTransactionExtension

DBSession = scoped_session(sessionmaker(extension=ZopeTransactionExtension()))
Base = declarative_base()

class SistemaOperacional():
    """
    Classe que define os sistemas operacionais
     
    """
    __tablename__ = ('so')
    
    id_so = Column(Integer, primary_key=True)
    te_desc_so = Column(UnicodeText, nullable=True)
    sg_so = Column(UnicodeText, nullable=True)
    te_so = Column(UnicodeText, nullable=False)
    in_mswindows = Column(UnicodeText, default='S')
    
    def __init__ (self, id_so, te_desc_so, sg_so, te_so, in_mswindows):
        """
        Metodo que chama as colunas
        """
        self.id_so = id_so
        self.te_desc_so = te_desc_so
        self.sg_so = sg_so
        self.te_so = te_so
        self.in_mswindows = in_mswindows
    
    def __repr__(self):
        """
        Metodo que passa a lista de parametros da classe
        """
        return "<SistemaOperacional('%s, %s, %s, %s, %s')>" % (self.id_so, self.te_desc_so, self.sg_so, self.te_so, self.in_mswindows)
class SistemaOperacionalContextFactory(SQLAlchemyORMContext):
    entity = SistemaOperacional
    def session_factory(self):
        return Session()


so = Table('so', Base.metadata,
                Column('id_so', Integer, primary_key=True),
                Column('te_desc_so', UnicodeText, nullable=True),
                Column('sg_so', UnicodeText, nullable=True),
                Column('te_so', UnicodeText, nullable=False),
                Column('in_mswindows', UnicodeText, default='S')
                )

mapper(SistemaOperacional, so)
