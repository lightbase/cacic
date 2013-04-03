from pyramid.paster import get_app
application = get_app('/srv/test/integrador/src/WSCacicNeo/development.ini', 'main')

#import wscacicneo
#import wscacicneo.monitor
#wscacicneo.monitor.start(interval=1.0)
