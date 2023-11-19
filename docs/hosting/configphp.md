# config.php

Here's all the variables in `config.php`:

|        Variable | Description / Example                                                                                                                                             |
|----------------:|:------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|      `BASE_URL` | The url generated for emails. Should be "https://id.byecorps.com" in production.                                                                                  |
|    `DB_ADDRESS` | The address for the database. Usually `localhost`                                                                                                                 |
|   `DB_USERNAME` | Username for connecting to the database.                                                                                                                          |
|   `DB_PASSWORD` | Password for the database.                                                                                                                                        |
|   `DB_DATABASE` | The database to connect to.                                                                                                                                       |
|   `PDO_OPTIONS` | `<br/>const PDO_OPTIONS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false,];` |
|        `DB_DSN` | `mysql:host='.DB_ADDRESS.';dbname='.DB_DATABASE.';charset=utf8mb4`, for PDO.                                                                                      |
|   `SENTRY_DSN ` | Used for Sentry.                                                                                                                                                  |
|     `MAIL_HOST` | SMTP host for emails                                                                                                                                              |
| `MAIL_USERNAME` | SMTP username                                                                                                                                                     |
| `MAIL_PASSWORD` | SMTP password                                                                                                                                                     |