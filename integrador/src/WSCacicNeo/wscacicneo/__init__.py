from pyramid.config import Configurator
from sqlalchemy import engine_from_config

from .models import (
    DBSession,
    Base,
    )


def main(global_config, **settings):
    """ This function returns a Pyramid WSGI application.
    """
    config = Configurator(settings=settings)
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
    config.scan()
    return config.make_wsgi_app()
