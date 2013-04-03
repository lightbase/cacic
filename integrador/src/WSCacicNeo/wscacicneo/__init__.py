from pyramid.config import Configurator
from sqlalchemy import engine_from_config

from .models import (
    DBSession,
    Base,
    )


def main(global_config, **settings):
    """ This function returns a Pyramid WSGI application.
    """
    engine = engine_from_config(settings, 'sqlalchemy.')
    DBSession.configure(bind=engine)
    Base.metadata.bind = engine
    config = Configurator(settings=settings)
    config.add_static_view('static', 'static', cache_max_age=3600)
    config.add_route('login', 'login')
    config.add_route('home', 'home')
    config.add_route('admin', 'administracao')
    config.add_route('dashboard', 'dashboard')
    config.add_route('reports', 'relatorios')
    config.add_route('diagnostic', 'diagnostico')
    config.add_route('master', 'master')
    config.add_route('pagina1', 'pagina1')
    config.add_route('pagina2', 'pagina2')
    config.add_route('pagina3', 'pagina3')
    config.add_route('pagina4', 'pagina4')
    config.add_route('pagina5', 'pagina5')
    config.add_route('pagina6', 'pagina6')
    config.scan()
    return config.make_wsgi_app()
