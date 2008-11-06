<?
// LDAP variables

$ldaphost = "dataprevasdfdsf";  // your ldap servers
$ldapport = 389;                 // your ldap server's port number

// Connecting to LDAP
$ldapconn = ldap_connect($ldaphost, $ldapport)
          or die("Could not connect to $ldaphost");
echo 'OK!';	
	  
$strMessage = '';
if ($_POST['btLogin']==' Login ')
	{	
	
	function ldap_binder($strDomainName,$strUserName,$strUserPassword)
		{
		$ldap_addr = $strDomainName;  // Change this to the IP address of the LDAP server
		$ldap_conn = ldap_connect($ldap_addr) or die("Couldn't connect!");
		ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldap_rdn  = $strDomainName."\\".$strUserName;
		$ldap_pass = $strUserPassword;

		// Authenticate the user against the domain controller
		$flag_ldap = ldap_bind($ldap_conn,$ldap_rdn,$ldap_pass);
		return $flag_ldap;
		}	

	// bind with appropriate dn to give update access
	$r = ldap_binder($_POST['frmDomainName'],$_POST['frmUserName'], $_POST['frmUserPassword']);
    echo 'R=> '.$r. "<br />";	

	

$attrs = get_entry_system_attrs( $ldap_conn, $ldap_rdn, $deref=LDAP_DEREF_NEVER );

for ($i=0; $i < count($attrs); $i++) 
    echo '=> '.$attrs[$i] . "<br />";
	
	$strMessage = '<font size=2 color=';
	// verify binding
	echo 'r0=>'.$r[0].'<br>';
	echo 'r1=>'.$r[1].'<br>';	
	if ($r) 
		$strMessage .= 'blue>Conexão Efetuada no Domínio!';
	else 
		$strMessage .= 'red>Conexão NÃO Efetuada no Domínio!';
	
	$strMessage .= '</font>';
	}
?>
<form id="form1" name="form1" method="post" action="">
<table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td><div align="center">Dom&iacute;nio</div></td>
    <td><div align="center"></div></td>
    <td><div align="center">Usu&aacute;rio</div></td>
    <td><div align="center"></div></td>
    <td><div align="center">Senha</div></td>
  </tr>
  <tr>
    <td>
      <label>
        <div align="center">
          <input name="frmDomainName" type="text" id="frmDomainName" size="20" maxlength="20" />
        </div>
      </label>        </td>
    <td><div align="center"></div></td>
    <td><div align="center">
      <input name="frmUserName2" type="text" id="frmUserName2" size="20" maxlength="20" />
    </div></td>
    <td><div align="center"></div></td>
    <td><div align="center">
      <input name="frmUserPassword" type="password" id="frmUserPassword" size="20" maxlength="20" />
    </div></td>
  </tr>
  <tr>
    <td colspan="5"><div align="center"><? echo $strMessage;?>
    </div>
      <div align="center"></div></td>
    </tr>
  
  <tr>
    <td colspan="5"><div align="center">
      <input type="submit" name="btLogin" id="btLogin" value=" Login " />
    </div></td>
  </tr>
</table>

</form>