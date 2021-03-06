<?php

require_once "/usr/share/kolab-webadmin/lib/functions.php";

function createDomain($domain_name)
{
global $auth;
        if ($auth->domain_info($domain_name) === false) {
                $attribs = array();
                $attribs['objectclass'] = array("top", "domainrelatedobject", "inetdomain");

                if ($auth->domain_add($domain_name, $attribs) === false) {
                        die("failed to add domain " .$domain_name);
                }
                return true;
        }

        return false;
}


$conf = Conf::get_instance();
$primary_domain = $conf->get('kolab', 'primary_domain');
$ldappassword = $conf->get('ldap', 'bind_pw');
$_SESSION['user'] = new User();
$valid = $_SESSION['user']->authenticate("cn=Directory Manager", $ldappassword, $primary_domain);

if ($valid === false) {
        die ("cannot authenticate user cn=Directory Manager");
}

$auth = Auth::get_instance();
echo "creating domain ".$conf->get('kolab', 'domainadmins_management_domain'). "\n";
createDomain($conf->get('kolab', 'domainadmins_management_domain'));

?>
