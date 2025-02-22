# Project Setup & Development Guide for the Bid Calculator tool coding challenge

## Prerequisites
This project uses **DDEV** for local development. Make sure you have the following installed:

- [Docker](https://www.docker.com/get-started)
- [DDEV](https://ddev.readthedocs.io/en/stable/)

---

## 🚀 Setting Up the Project

### 1. Install DDEV and Start the Project
If DDEV is not installed, follow the [official guide](https://ddev.readthedocs.io/en/stable/#installation).

Once installed, navigate to your project directory and run:

```bash
ddev config
ddev start
```

This will set up the DDEV environment.

### 2. Install Dependencies
Inside the DDEV container, install project dependencies:

```bash
ddev exec composer install
```

---

## 🧪 Running PHP Unit Tests

To execute the test suite, run the following command inside the DDEV environment:

```bash
ddev exec ./vendor/bin/phpunit
```

To run tests for the Bid Calculator tool:

```bash
ddev exec ./vendor/bin/phpunit tests/Controller/BidCalculatorControllerTest.php
```

---

## 📌 Useful DDEV Commands

| Command                     | Description                                  |
|-----------------------------|----------------------------------------------|
| `ddev start`                | Start the DDEV environment                   |
| `ddev stop`                 | Stop all DDEV services                       |
| `ddev restart`              | Restart the project                          |
| `ddev exec composer install`| Install PHP dependencies                     |
| `ddev exec phpunit`         | Run PHPUnit tests                            |
| `ddev ssh`                  | Access the container's shell                 |

---

## ❓ Troubleshooting

- **DDEV is not working properly?** Try running:
  ```bash
  ddev poweroff
  ddev start
  ```

- **Dependencies not found?** Ensure `composer install` has been executed.

---

## 🛠️ Resources

- [DDEV Documentation](https://ddev.readthedocs.io/)
- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

