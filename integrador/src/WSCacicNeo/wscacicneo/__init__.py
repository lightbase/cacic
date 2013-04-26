from pyramid.config import Configurator
from sqlalchemy import engine_from_config
from sqlalchemy.orm import sessionmaker
from sqlalchemy import create_engine
engine = create_engine('postgresql://renan@localhost/Cacic')


from .models import (
    DBSession,
    Base,
    )

def db(request):
    maker = request.registry.dbmaker
    session = maker()

    def cleanup(request):
        session.close()
    request.add_finished_callback(cleanup)

    return session


def main(global_config, **settings):
    """ This function returns a Pyramid WSGI application.
    """
    config = Configurator(settings=settings)
    engine = engine_from_config(settings, prefix='sqlalchemy.')
    config.registry.dbmaker = sessionmaker(bind=engine)
    config.add_request_method(db, reify=True)
    config.add_route('home', 'home')
    config.add_route('admin', 'administracao')
    config.add_route('dashboard', 'dashboard')
    config.add_route('relatorios', 'relatorios')
    config.add_route('ferramentas', 'ferramentas')
    config.add_route('usuario', 'usuario')
    config.add_route('ajuda', 'ajuda')
    config.add_route('diagnostic', 'diagnostico')
    config.add_route('downloads', 'downloads')
    config.add_route('estatisticas', 'estatisticas')
    config.add_route('busca', 'busca')
    config.add_route('mensagens', 'mensagens')
    config.add_route('login', 'login')
    config.add_static_view('static', 'static', cache_max_age=3600 )
    config.scan()
    return config.make_wsgi_app()
