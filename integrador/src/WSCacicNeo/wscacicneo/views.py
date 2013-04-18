from pyramid.response import Response
from pyramid.view import view_config
def simple_app(environ, start_response):
    start_response('200 OK', [('Content-type', 'text/html')])
    return ['<html><body>Hello World</body></html>']
class GoogleRefMiddleware(object):
    def __init__(self, app):
        self.app = app

    def __call__(self, environ, start_response):
        environ['google'] = False
        if 'HTTP_REFERER' in environ:
            if environ['HTTP_REFERER'].startswith('http://google.com'):
                environ['google'] = True
        return self.app(environ, start_response)




#from sqlalchemy.exc import DBAPIError

#from .models import (
#    DBSession,
#    MyModel,
#    )


#@view_config(route_name='home', renderer='templates/mytemplate.pt')
	# existe um dicionário chamado request.params que contém tudo o que é enviado para a página

conn_err_msg = """\
Pyramid is having a problem using your SQL database.  The problem
might be caused by one of the following things:

1.  You may need to run the "initialize_WSCacicNeo_db" script
    to initialize your database tables.  Check your virtual 
    environment's "bin" directory for this script and try to run it.

2.  Your database server may not be running.  Check that the
    database server referred to by the "sqlalchemy.url" setting in
    your "development.ini" file is running.

After you fix the problem, please restart the Pyramid application to
try it again.
"""
@view_config(route_name='login', renderer='templates/login.pt')
def my_view0(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='home', renderer='templates/home.pt')
def my_view1(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='admin', renderer='templates/admin.pt')
def my_view2(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='dashboard', renderer='templates/dashboard.pt')
def my_view3(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='diagnostic', renderer='templates/diagnostic.pt')
def my_view5(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='estatisticas', renderer='templates/estatisticas.pt')
def my_view7(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='busca', renderer='templates/busca.pt')
def my_view8(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='downloads', renderer='templates/downloads.pt')
def my_view9(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='relatorios', renderer='templates/relatorios.pt')
def my_view10(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='mensagens', renderer='templates/mensagens.pt')
def my_view11(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='ajuda', renderer='templates/ajuda.pt')
def my_view12(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='usuario', renderer='templates/usuario.pt')
def my_view13(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='ferramentas', renderer='templates/ferramentas.pt')
def my_view14(request):
    return {'project':'WSCacicNeo'}
