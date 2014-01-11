<?php
    /* openldap settings */
    $settings['openldap']['servers']   = 'mmldap';
    $settings['openldap']['port']      = 636;	
    $settings['openldap']['tls']       = '';
    $settings['openldap']['tls-url']   = '';
    $settings['openldap']['bind-dn']   = '';
    $settings['openldap']['base-dn']   = 'dc=gov,dc=br';
    $settings['openldap']['username']  = 'anderson.peterle';
    $settings['openldap']['password']  = 'mmmjan01012345';
    $settings['openldap']['protocol']  = 3;
	
    $settings['openldap']['referrals'] = 0;
    $settings['openldap']['timelimit'] = 10;
	$settings['openldap']['timeout']   = 10;

	echo '<pre>'; echo 'Incluindo Classe...';
    if (file_exists('ldap_class.php')) 
		{
		echo 'OK!</pre>';		
		include 'ldap_class.php';
		
		echo '<pre>'; echo 'Instanciando Objeto...'; 		
		if ($openldap = openldap::instance($settings['openldap']))
			{
			echo 'OK!</pre>';
			echo '<pre>'; print_r($openldap); echo '</pre>';
			}
		else
			echo 'Oops!</pre>';
			
		echo '<pre>'; echo 'Efetuando Pesquisa...';		
		$filter='(uid=anderson.peterle)';		
		if ($results = $openldap->search($settings['openldap']['base-dn'], $filter))
			{
			echo 'OK!</pre>';					
			echo '<pre>'; print_r($results); echo '</pre>';

			echo '<pre>'; echo 'Exibindo Resultados...'; echo '</pre>';
			echo '<pre>'; print_r($openldap->results($results)); echo '</pre>';	
			echo '<pre>'; echo '********************************************'; echo '</pre>';
			    
			echo '<pre>'; echo 'Autenticando Usuário...'; echo '</pre>';
			$results = $openldap->authenticate($settings['openldap'], 'username', 'password');
			echo '<pre>'; var_dump($results); echo '</pre>';
			echo '<pre>'; echo '********************************************'; echo '</pre>';
			}
		else
			echo 'Oops!</pre>';		
			
		}
	else
		echo 'Oops!</pre>';
		
	echo '<pre>'; echo 'Unloading openldap authentication libraries';echo '</pre>';

	unset($openldap);
		
    ?>
