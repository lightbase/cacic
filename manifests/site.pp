# -*- mode: ruby -*-
# vi: set ft=ruby :

Exec { path => '/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin' }

include timezone
include user
#include apt
include apache
include postgresql
include php
include symfony
include software

class timezone {
  package { "tzdata":
    ensure => latest,
    require => Class['apt']
  }

  file { "/etc/localtime":
    require => Package["tzdata"],
    source => "file:///usr/share/zoneinfo/${tz}",
  }
}

class user {
  exec { 'add user':
    command => "sudo useradd -m -G sudo -s /bin/bash ${user}",
    unless => "id -u ${user}"
  }

  exec { 'set password':
    command => "echo \"${user}:${password}\" | sudo chpasswd",
    require => Exec['add user']
  }

  # Prepare user's project directories
  file { ["/home/${user}/projects",
          "/home/${user}/public_html"
          ]:
    ensure => directory,
    owner => "${user}",
    group => "${user}",
    require => Exec['add user']
  }
}

class apache {

  package { 'apache2':
    ensure => latest,
    require => Class['apt']
  }

  service { 'apache2':
    ensure => running,
    enable => true,
    require => Package['apache2']
  }

  class { 'apache':
    default_vhost => false,
  }

  host { "${domain_name}":
    ip => '127.0.0.1',
  }

  apache::vhost { "${domain_name}":
    port => 80,
    docroot => "/home/${user}/public_html/${domain_name}",
    fallbackresource => '/app.php',
    options => ['-Indexes','+FollowSymLinks','+MultiViews'],
    override => ['all'],
    suphp_addhandler => 'x-httpd-php',
    suphp_engine     => 'on',
    suphp_configpath => '/etc/php5/apache2',
    directories      => [
      { 'path'  => "/home/${user}/public_html/${domain_name}",
        'suphp' => {
          user  => "${user}",
          group => "${user}",
        },
      },
    ],
  }

  apache::vhost { "ssl.${domain_name}":
    port => 443,
    docroot => "/home/${user}/public_html/${domain_name}",
    fallbackresource => '/app.php',
    options => ['-Indexes','+FollowSymLinks','+MultiViews'],
    override => ['all'],
    ssl => true,
    suphp_addhandler => 'x-httpd-php',
    suphp_engine     => 'on',
    suphp_configpath => '/etc/php5/apache2',
    directories      => [
      { 'path'  => "/home/${user}/public_html/${domain_name}",
        'suphp' => {
          user  => "${user}",
          group => "${user}",
        },
      },
    ],
  }

}

class postgresql {

  class { 'postgresql::globals':
    version             => '9.4',
    manage_package_repo => true,
    encoding            => "UTF8",
    #locale              => "pt_BR.UTF-8",
    # TODO: remove the next line after PostgreSQL 9.4 release
    postgis_version     => '2.1',
  }->
  class { 'postgresql::server':
    listen_addresses => '127.0.0.1',
    port   => 5432,
    ip_mask_allow_all_users    => '127.0.0.1/32',
  }

  postgresql::server::role { "${user}":
    superuser => true,
    require   => Class['postgresql::server']
  }

  postgresql::server::db { "${db_name}":
    encoding => 'UTF8',
    user => "${db_user}",
    owner => "${db_user}",
    password => postgresql_password("${db_user}", "${db_password}"),
    require  => Class['postgresql::server']
  }

  postgresql::server::role { "${db_user}":
    createdb => true,
    require  => Class['postgresql::server']
  }

  package { 'libpq-dev':
    ensure => installed
  }

  package { 'postgresql-contrib':
    ensure  => installed,
    require => Class['postgresql::server'],
  }
}


class php {

  package { 'php5':
    ensure => latest,
    require => Class['apt']
  }

  package { ['php5-pgsql', 'php5-gd', 'php5-mcrypt', 'libapache2-mod-php5', 'php5-ldap', 'php-pear', 'openjdk-7-jre', 'php5-intl', 'php5-cli', 'php5-common']:
    ensure => latest,
    require => Class['apt']
  }

  php::config { 'memory_limit':
    file    => '/etc/php5/apache2/php.ini',
    setting => 'memory_limit',
    value => '512M',
    require => Package['php5']
  }

  php::config { 'memory_limit':
    file    => '/etc/php5/apache2/php.ini',
    setting => 'date.timezone',
    value => "${tz}",
    require => Package['php5']
  }
}

class symfony {
  package { 'webenv':
    require => Class['php', 'user', 'postgresql', 'apache']
  }

  file { 'parameters':
    path => "/home/${user}/projects/${domain_name}/app/config",
    ensure => file,
    content => template("${inc_file_path}/symfony/parameters.yml.erb"),
    require => Package['webenv']
  }

  exec { 'composer install':
    command => "php composer.phar install",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => File['parameters']
  }

  exec { 'cache clear':
    command => "php app/console cache:clear --env=prod",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['composer install']
  }

  exec { 'assets install':
    command => "php app/console assets:install --symlink --env=prod",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['cache clear']
  }

  exec { 'assetic dump':
    command => "php app/console assetic:dump --env=prod",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['assets install']
  }

  exec { 'unittests':
    command => "php phpunit.phar -c app",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['assetic dump']
  }

  exec { 'schema update':
    command => "php app/console doctrine:schema:update --force",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['unittests']
  }

  exec { 'migrations migrate':
    command => "php app/console doctrine:migrations:migrate",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['schema update']
  }

  exec { 'cache clear2':
    command => "php app/console cache:clear --env=prod",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['migrations migrate']
  }

  exec { 'monolog browser':
    command => "php app/console lexik:monolog-browser:schema-create",
    cwd => "/home/${user}/projects/${domain_name}",
    user => $user,
    require => Exec['cache clear2']
  }

  file {"/home/${user}/public_html/${domain_name}":
    ensure => "link",
    target => "/home/${user}/projects/${domain_name}/web",
    notify => Service["apache2"],
    replace => yes,
    force => true,
    require => Exec['monolog browser']
  }
}

class software {
  package { 'git':
    ensure => latest,
    require => Class['apt']
  }

  package { 'vim':
    ensure => latest,
    require => Class['apt']
  }

  package { 'libffi-dev':
    ensure => latest,
    require => Class['apt']
  }
}
