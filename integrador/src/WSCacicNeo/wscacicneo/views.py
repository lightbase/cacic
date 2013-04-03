from pyramid.response import Response
from pyramid.view import view_config

#from sqlalchemy.exc import DBAPIError

#from .models import (
#    DBSession,
#    MyModel,
#    )


#@view_config(route_name='home', renderer='templates/mytemplate.pt')
#def my_view(request):
#    try:
#        one = DBSession.query(MyModel).filter(MyModel.name == 'one').first()
#    except DBAPIError:
#        return Response(conn_err_msg, content_type='text/plain', status_int=500)
#    return {'one': one, 'project': 'WSCacicNeo'}

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

@view_config(route_name='reports', renderer='templates/reports.pt')
def my_view4(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='diagnostic', renderer='templates/diagnostic.pt')
def my_view5(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='master', renderer='templates/master.pt')
def my_view6(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina1', renderer='templates/pagina1.pt')
def my_view7(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina2', renderer='templates/pagina2.pt')
def my_view8(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina3', renderer='templates/pagina3.pt')
def my_view9(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina4', renderer='templates/pagina4.pt')
def my_view10(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina5', renderer='templates/pagina5.pt')
def my_view11(request):
    return {'project':'WSCacicNeo'}

@view_config(route_name='pagina6', renderer='templates/pagina6.pt')
def my_view12(request):
    return {'project':'WSCacicNeo'}


