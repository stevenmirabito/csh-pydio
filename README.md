# CSH Pydio
This repository contains plugins that help integrate CSH branding and services into Pydio.

## Installation
This guide assumes you have installed and configured Pydio using system packages under CentOS 7. Adjust paths accordingly for your installation.

### 1. Clone repository

```
cd /opt
git clone https://github.com/stevenmirabito/csh-pydio.git
```

Once the repository has been cloned, continue to either step 2a (recommended) or 2b.

### 2a. Link directories

```
chmod -R root:apache /opt/csh-pydio
ln -s /opt/csh-pydio/access.csh_services /usr/share/pydio/plugins/access.csh_services
ln -s /opt/csh-pydio/gui.csh_theme /usr/share/pydio/plugins/gui.csh_theme
```

### 2b. Copy plugins into Pydio installation

```
cp -r /opt/csh-pydio/access.csh_services /usr/share/pydio/plugins/
cp -r /opt/csh-pydio/gui.csh_theme /usr/share/pydio/plugins/
chmod -R root:apache /usr/share/pydio/plugins/access.csh_services
chmod -R root:apache /usr/share/pydio/plugins/gui.csh_theme
```

### 3. Install hook
As the access plugin must be able to perform string replacement early in the application, it needs to be manually added as a hook. At the end of `/etc/pydio/bootstrap_context.php`, add the following lines:

```php
// CSH Services Hook
require_once AJXP_INSTALL_PATH . "/plugins/access.csh_services/class.CSHServices.php";
AJXP_Controller::registerIncludeHook("vars.filter", array("CSHServices", "filterVars"));
AJXP_Controller::registerIncludeHook("xml.filter", array("CSHServices", "filterVars"));
```

### 4. Clear Pydio's plugin cache

```
rm /var/cache/pydio/plugins_cache.ser
rm /var/cache/pydio/plugins_requires.ser
```

## LDAP Configuration
Once the plugins have been installed, configure LDAP to allow users to log in.

*Settings -> Application Core -> Authentication*

| Option             | Value                          |
|--------------------|--------------------------------|
| Instance Type      | LDAP/AD Directory              |
| LDAP URL           | ldap.csh.rit.edu               |
| Protocol           | SSL (ldaps)                    |
| LDAP Port          | 636                            |
| LDAP Bind Username | _User/app account DN_          |
| LDAP Bind Password | _User/app account password_    |
| People DN          | ou=Users,dc=csh,dc=rit,dc=edu  |
| LDAP Filter        | objectClass=person             |
| User Attribute     | uid                            |
| Groups DN          | ou=Groups,dc=csh,dc=rit,dc=edu |
| LDAP Groups Filter | objectClass=group              |
| Group Attribute    | cn                             |
| Role Prefix        | csh                            |
| LDAP Attribute     | homeDirectory                  |
| Mapping Type       | Plugin Parameter               |
| Plugin Parameter   | core.conf/LDAP_USER_HOME_DIR   |
| Admin Login        | _Your UID_                     |
