# BIMALEGENDFX

BIMALEGENDFX is a small, responsive creative-studio website built with PHP. It introduces the studio, presents its services and team, and includes a project inquiry form whose submissions can be reviewed on a password-protected local messages page.

## Features

- Studio home, about, services, and contact pages.
- Service and team content maintained in shared PHP data arrays.
- Individual service detail pages with a friendly 404 response for unknown services.
- Contact form with server-side validation, sticky values, CSRF protection, and Post/Redirect/Get.
- Contact submissions stored as JSON Lines in `storage/messages.jsonl`.
- Password-protected messages page with newest-first sorting and a submission count.
- Escaped dynamic output and CSS styling for desktop and mobile layouts.

## Tech stack

- PHP 8+ (strict typing on data-driven pages)
- HTML5
- CSS3
- Apache through XAMPP
- JSON Lines for local form-submission storage

## Run with XAMPP

1. Install and start **Apache** from the XAMPP Control Panel.
2. Place the project folder at:

   ```text
   C:\xampp\htdocs\swd\04-php-site
   ```

3. Open the site at:

   ```text
   http://localhost/swd/04-php-site/
   ```

4. The messages page is available at:

   ```text
   http://localhost/swd/04-php-site/messages.php
   ```

   Before using the messages page, configure the `BIMALEGENDFX_MESSAGES_PASSWORD` environment variable in Apache with a unique password. Do not store the password in source control. The page will remain unavailable until this variable is configured.

The contact form creates `storage/` if it does not exist and writes messages to `storage/messages.jsonl`. Apache access to that folder is denied by `storage/.htaccess`, and the generated submissions file is excluded from Git.

## Project structure

```text
04-php-site/
├── assets/
│   └── css/
│       └── style.css
├── includes/
│   ├── config.php
│   ├── data.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── storage/
│   └── .htaccess
├── about.php
├── contact.php
├── index.php
├── messages.php
├── service.php
├── services.php
└── README.md
```

## What I learned

- **PHP forms and validation:** _[Add what you learned about processing and validating form input.]_
- **Sessions and CSRF:** _[Add what you learned about session state and CSRF tokens.]_
- **Safe output:** _[Add what you learned about escaping dynamic HTML output.]_
- **File storage:** _[Add what you learned about writing and reading JSON Lines.]_
- **Post/Redirect/Get:** _[Add what you learned about redirecting after a successful POST.]_

## Learning-project security note

The messages page password is read from the server environment so it is not stored in source control. This simple login has no rate limiting or account management. Do not use it with real or sensitive submissions without adding appropriate access controls, rate limiting, HTTPS, and secure session configuration.
