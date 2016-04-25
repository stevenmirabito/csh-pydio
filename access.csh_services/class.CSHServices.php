<?php
/*
 * CSH Service Integrations for Pydio
 * Author: Steven Mirabito (smirabito@csh.rit.edu)
 */

defined('AJXP_EXEC') or die( 'Access not allowed');

/**
 * Integrates CSH services into Pydio
 * @package AjaXplorer_Plugins
 * @subpackage Action
 */
class CSHServices extends AJXP_Plugin
{
    /**
     * This is an example of filter that can be hooked to the AJXP_VarsFilter,
     * for using your own custom variables in the repositories configurations.
     * In this example, this variable does exactly what the current AJXP_USER variable do.
     * Thus, once hooked, you can use CUSTOM_VARIABLE_USER in e.g. a repository PATH, and
     * build this path dynamically depending on the current user logged.
     * Contrary to other standards hooks like node.info, this cannot be added via XML manifest
     * as it happen too early in the application, so it must be declared directly inside the conf.php
     *
     * @param String $value
     */
    public static function filterVars(&$value)
    {
        if (AuthService::getLoggedUser() != null) {
            if (is_string($value) && strpos($value, "LDAP_USER_HOME_DIR") !== false) {
                $user_id = AuthService::getLoggedUser()->getId();
                $userObject = ConfService::getConfStorageImpl()->createUserObject($user_id);
                $ldap_home_dir = $userObject->mergedRole->filterParameterValue("core.conf", "LDAP_USER_HOME_DIR", AJXP_REPO_SCOPE_ALL, "");
                $value = str_replace("LDAP_USER_HOME_DIR", $ldap_home_dir, $value);
            }
        }
    }
}
