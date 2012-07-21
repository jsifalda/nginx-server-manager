# NGINX server manager

**PHP script (used from command line) for fast creating virtual host in nginx server**

##Installing
- download source files
- copy server-manager.phpc to your server root directory
- enjoy it!

## Usage
	
	sudo php <path>/createhost.phpc -n <name-of-new-server>

**SUDO** is required!

##Parameters
- -n: Name of virtual host (required)
- -x: Nginx root dir. (Path to nginx config dir)
- -s: Server root dir (Path to your server folders)
- -d: Remove server?
- -r: Remove server with source code?