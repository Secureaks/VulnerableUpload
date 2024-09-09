# Vulnerable Upload Platform

> [!CAUTION]
> This application is intended to be vulnerable. Do not deploy it in a public environment.

This is a vulnerable platform that allows users to upload unsecure files to the server. The platform is vulnerable to a 
number of uploads attacks.

## Deployment

This guide is based on Ubuntu 22.04. For other distributions, you will need to adapt the commands.

### Install the dependencies

```bash
apt install php-xml php-intl php-curl php-mbstring php-mysql php-sqlite3 php-zip php-gd php-imagick
```

### Clone the repository

```bash
git clone https://github.com/Secureaks/VulnerableUpload.git
cd VulnerableUpload
```

### Set the environment variables

```bash
cp .env .env.local
nano .env.local
```

### Run the following commands

```bash
composer install
php bin/console doctrine:database:create # May be optional if the database already exists
php bin/console doctrine:schema:update --force
```

### Start the dev server

```bash
symfony serve
```

Or:

```bash
php -S 0.0.0.0:8000 -t public
```

The application will be available at [http://localhost:8000](http://localhost:8000). The port may vary depending on your
configuration.

If you want to test the `.htaccess` exploit, the application needs to be deployed on an Apache server. You can use docker
to deploy the application on an Apache server.

### Docker

You can start the docker container by running the following commands:

```bash
docker compose up -d
```

The application will be available at [http://localhost:8000](http://localhost:8000).

## List of vulnerabilities

You can find below the list of upload vulnerabilities available in the application. The source code is also documented to allow
you to understand how the vulnerabilities are implemented.

- No protection at all: The application does not check the file type or content: `/1`
- Content-Type check from request: The application checks the content type from the request: `/2`
- Content-Type check from file: The application checks the content type from the file: `/3`
- File extension check: The application checks the file extension in a non-secure way: `/4`
- File extension check with a blacklist: The application checks the file extension with a blacklist (.htaccess bypass): `/5`

## Credits

This project is provided by [Secureaks](https://secureaks.com).

## Contributing

If you want to contribute to this project, fill free to open an issue or a pull request with your changes.

## License

This project is licensed under the ATTRIBUTION-NONCOMMERCIAL-SHAREALIKE 4.0 INTERNATIONAL (CC BY-NC-SA 4.0) license.

You are free to:

- Share — copy and redistribute the material in any medium or format
- Adapt — remix, transform, and build upon the material

The licensor cannot revoke these freedoms as long as you follow the license terms.

Under the following terms:

- Attribution — You must give appropriate credit , provide a link to the license, and indicate if changes were made . You may do so in any reasonable manner, but not in any way that suggests the licensor endorses you or your use.
- NonCommercial — You may not use the material for commercial purposes .
- ShareAlike — If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.

No additional restrictions — You may not apply legal terms or technological measures that legally restrict others from doing anything the license permits.

See the [LICENSE](LICENSE) file for details.

