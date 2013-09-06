#!/bin/sh

rm -rf pycacic
mkdir -p pycacic/DEBIAN pycacic/usr/share/ pycacic/usr/share/applications pycacic/etc/xdg/autostart pycacic/etc/cron.hourly pycacic/etc/init.d

cp control pycacic/DEBIAN
cp postinst pycacic/DEBIAN
cp prerm pycacic/DEBIAN
cp postrm pycacic/DEBIAN
cp -a /usr/share/pycacic pycacic/usr/share
cp /usr/share/applications/pycacic.desktop pycacic/usr/share/applications/pycacic.desktop
cp /etc/xdg/autostart/pycacic.desktop pycacic/etc/xdg/autostart/pycacic.desktop
cp /etc/cron.hourly/chksis pycacic/etc/cron.hourly/chksis
cp /etc/init.d/cacic pycacic/etc/init.d/cacic

chown -R root:root pycacic/
chmod -R 0755 pycacic/DEBIAN/*

nome=pyCACIC_2.6.0.2.deb
dpkg-deb -b pycacic /tmp/$nome
echo "Gerado pacote .deb em: /tmp/$nome"







