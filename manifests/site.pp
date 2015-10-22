# -*- mode: ruby -*-
# vi: set ft=ruby :

Exec { path => '/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin' }

include timezone
include user
include apache_install
include postgresql
include php_install
include symfony
include software

# PHP
include php
include php::apt
include php::params
include php::pear


class timezone {
  package { "tzdata":
    ensure => latest
  }

  file { "/etc/localtime":
    require => Package["tzdata"],
    source => "file:///usr/share/zoneinfo/${tz}",
  }

  class { 'locales':
    default_locale  => $default_locale,
    locales         => $locales,
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

  # Variavel de ambiente do composer
  file { "user-profile":
    path => "/home/${user}/.profile",
    owner => "${user}",
    group => "${user}",
    content => inline_template("COMPOSER_HOME=/home/${user}/.composer"),
    require => Exec['add user']
  }

  exec {'load profile':
    command => "bash -c 'source /home/${user}/.profile'",
    require => File['user-profile']
  }

}

class apache_install {

  class { 'apache':
    default_vhost => false,
    default_mods  => false,
    mpm_module    => 'worker'
  }

  host { "${domain_name}":
    ip => '127.0.0.1',
  }

  # Módulos obrigatórios
  apache::mod { 'rewrite': 
  }

  apache::mod { 'proxy_fcgi': 
  }

  class { 
    'apache::mod::ssl':
  }

  class { 
    'apache::mod::alias':
  }

  class { 
    'apache::mod::proxy':
  }

  apache::vhost { "${domain_name}":
    port => 80,
    docroot => "/home/${user}/public_html/${domain_name}",
    directories => [
      {
        path => "\\.php" ,
        provider => 'filesmatch',
        custom_fragment => 'SetHandler proxy:fcgi://127.0.0.1:9000',
      },
      {
        path => "/home/${user}/public_html/${domain_name}",
        provider => 'directory',
        allow_override => 'all',
        options => ['-Indexes','+FollowSymLinks','+MultiViews'],
      }
    ],
  }

  apache::vhost { "ssl.${domain_name}":
    port => 443,
    docroot => "/home/${user}/public_html/${domain_name}",
    directories => [
      {
        path => "\\.php" ,
        provider => 'filesmatch',
        custom_fragment => 'SetHandler proxy:fcgi://127.0.0.1:9000',
      },
      {
        path => "/home/${user}/public_html/${domain_name}",
        provider => 'directory',
        allow_override => 'all',
        options => ['-Indexes','+FollowSymLinks','+MultiViews'],
      }
    ],
    ssl => true,
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


class php_install($version = 'latest') {

  # Extensions must be installed before they are configured
  Php::Extension <| |> -> Php::Config <| |>

  # Ensure base packages is installed in the correct order
  # and before any php extensions
  Package['php5-common']
  -> Package['php5-dev']
  -> Package['php5-cli']
  -> Php::Extension <| |>

  class {
    # Base packages
    [ 'php::dev', 'php::cli' ]:
      ensure => $version;

    # PHP extensions
    [
      'php::extension::curl', 'php::extension::gd',
      'php::extension::mcrypt', 'php::extension::pgsql', 'php::extension::ldap'
    ]:
      ensure => $version;

    [ 'php::extension::igbinary' ]:
      ensure => installed
  }

  # Install the INTL extension
  php::extension { 'php5-intl':
    ensure    => $version,
    package   => 'php5-intl',
    provider  => 'apt'
  }

  # Install the CGI extension
  php::extension { 'php5-cgi':
    ensure    => $version,
    package   => 'php5-cgi',
    provider  => 'apt'
  }

  # Install the SQLite extension (for tests)
  php::extension { 'php5-sqlite':
    ensure    => $version,
    package   => 'php5-sqlite',
    provider  => 'apt'
  }

  create_resources('php::config', hiera_hash('php_config', {}))
  create_resources('php::cli::config', hiera_hash('php_cli_config', {}))

  class { 'php::fpm':
    ensure => $version
  }

  create_resources('php::fpm::pool',  hiera_hash('php_fpm_pool', {}))
  create_resources('php::fpm::config',  hiera_hash('php_fpm_config', {}))

  Php::Extension <| |> ~> Service['php5-fpm']

  exec { "restart-php5-fpm":
    command  => "service php5-fpm restart",
    schedule => hourly,
    require => Class['php::fpm']
  }

  php::fpm::pool { 'www':
    ensure => 'present',
    user => "${user}",
    group => "${group}"
  }


  php::fpm::config { "memory_limit":
    file    => '/etc/php5/cgi/php.ini',
    setting => 'memory_limit',
    value => '512M',
    section => 'PHP'
  }

  php::fpm::config { "date.timezone":
    file    => '/etc/php5/cgi/php.ini',
    setting => 'date.timezone',
    value => "${tz}",
    section => 'Date'
  }

  php::cli::config { "memory_limit2":
    setting => 'memory_limit',
    value => '512M',
    section => 'PHP',
  }

  php::cli::config { "date.timezone2":
    setting => 'date.timezone',
    value => "${tz}",
    section => 'Date',
  }

}

class symfony {

  file { 'parameters':
    path => "/home/${user}/projects/${domain_name}/app/config/parameters.yml",
    ensure => file,
    content => template("${inc_file_path}/symfony/parameters.yml.erb"),
    require => Class['php_install', 'user', 'postgresql', 'apache_install', 'software', 'timezone']
  }

  exec { 'composer install':
    command => "bash -c 'export COMPOSER_HOME=/home/${user}/.composer && php composer.phar install'",
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

  apt::source { 'non-free':
    location => 'http://ftp.pop-sc.rnp.br/debian/',
    release  => 'wheezy',
    repos    => 'non-free',
    include  => {
      'deb' => true,
    },
    notify => Exec['apt_update']
  }

  package { 'git':
    ensure => latest
  }

  package { 'vim':
    ensure => latest
  }

  package { 'libffi-dev':
    ensure => latest
  }

  package {'openjdk-7-jre':
    ensure => latest
  }


}
